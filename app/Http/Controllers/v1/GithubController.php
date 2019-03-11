<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\RequestIssues;
use App\Http\Requests\RequestRepositories;
use App\Http\Requests\RequestIssuesSearch;
use App\Http\Requests\RequestRepositoriesSearch;

use Illuminate\Support\Facades\Redis;

use Validator;


class GithubController extends Controller
{

	private $client;
	private $redis;

    public function __construct()
    {
        $this->client = new \Github\Client();
        $this->redis = Redis::connection();
    }

	private function generateJSON($key, $value){
		return response()->json([
							    "success" => true,
							    "data" => [
							    	$key => $value
							      ]
							]);
	}

	private function getFormatItem($issue, $format_keys){
		$new_issue = [];
		foreach ($format_keys as $key => $value)
			$new_issue[$key] = $issue[$value];
		return $new_issue;
	}



	public function getIssues(RequestIssues $request, $userName, $repositoryName){
		$fromDb = $request['fromDb'];
		$page = $request['page'];
		$perPage = $request['perPage'];

		//собираем строку для ключа кэша
		$find_string = __FUNCTION__.$userName.$repositoryName.$page.'.'.$perPage;
		$issues_format = json_decode($this->redis->get($find_string));

		//если кэша нет и fromdb == true
		if (!$issues_format && $fromDb){

			try {
				$issues = array_slice($this->client->api('issue')->all($userName, $repositoryName), ($page-1)*$perPage, $perPage);
			} catch (Exception $e) {
				abort(500);
			}
			
			$issues_format = [];
			foreach ($issues as $issue)
				$issues_format[] = self::getFormatItem($issue, ['id' => 'id' ,'title' => 'title','number'=>'number', 'state'=>'state']);
			$this->redis->set($find_string, json_encode($issues_format));
		}
		return self::generateJSON("issues", $issues_format);
	}	

	public function getRepositories(RequestRepositories $request, $userName){

		$fromDb = $request['fromDb'];
		$page = $request['page'];
		$perPage = $request['perPage'];

		$find_string = __FUNCTION__.$userName.$page.'.'.$perPage;
		$repos_format = json_decode($this->redis->get($find_string));

		if (!$repos_format && $fromDb){
			try {
				$repos = array_slice($this->client->api('user')->repositories($userName), ($page-1)*$perPage, $perPage);
			} catch (Exception $e) {
				abort(500);
			}
			$repos_format = [];
			foreach ($repos as $rep)
				$repos_format[] = self::getFormatItem($rep, ['id' => 'id' ,'name' => 'name','description'=>'description', 'private'=>'private', 'language'=>'language']);
			$this->redis->set($find_string, json_encode($repos_format));
		}
		return self::generateJSON("repositories", $repos_format);
	}	

	public function getIssuesSearch(RequestIssuesSearch $request, $userName){

		$fromDb = $request['fromDb'];
		$page = $request['page'];
		$perPage = $request['perPage'];
		
		$user = 'user:'.$userName;
		$state = isset($request['state']) ? sprintf(' %s:%s ', 'state', $request['state']) : ' ';

		$params = $request['title'].' in:name '.$user.$state.$request['number'].' in:number';

		$find_string = __FUNCTION__.$params.$page.'.'.$perPage.$page.'.'.$perPage;
		$issues_format = json_decode($this->redis->get($find_string));

		if (!$issues_format && $fromDb){
			try {
				$issues = array_slice($this->client->api('search')->issues($params)['items'], ($page-1)*$perPage, $perPage);
			} catch (Exception $e) {
				abort(500);
			}

			$issues_format = [];
			foreach ($issues as $issue){
				 $new_item = self::getFormatItem($issue, ['id' => 'id' ,'title' => 'title','number'=>'number', 'state'=>'state']);
				 $new_item['github_id'] = $issue['user']['id'];
				 /*
					repository_id приходится забирать достаточно костыльно. Но функционирует.
					Другого способа не нашел
				 */
				 $name_rep = array_slice(explode('/', $issue['repository_url']), -1)[0];
				 try {
				 	$new_item['repository_id'] = $this->client->api('search')->repositories($name_rep.' user:'.$issue['user']['login'])['items'][0]['id'];
				 } catch (Exception $e) {
				 	abort(500);
				 }
				 $issues_format[] = $new_item;
			}
			$this->redis->set($find_string, json_encode($issues_format));
		}
		return self::generateJSON("issues", $issues_format);
	}	

	public function getRepositoriesSearch(Request $request, $userName){

		$fromDb = $request['fromDb'];
		$page = $request['page'];
		$perPage = $request['perPage'];

		$user = 'user:'.$userName;
		$title = $request->input('title','');
		$language = isset($request['language']) ? sprintf(' language:%s', $request['language']):'';
		$private = $request->input('private','') == 'true' ? (' is:private') : ($request->input('private','') == 'false' ? (' is:public') : '');

		$params = $title.$user.$language.$private;

		$find_string = __FUNCTION__.$params.$page.'.'.$perPage;
		$repos_format = json_decode($this->redis->get($find_string));

		if (!$repos_format && $fromDb){
			try {
				$repos = array_slice($this->client->api('search')->repositories($params)['items'], ($page-1)*$perPage, $perPage);;
			} catch (Exception $e) {
				abort(500);
			}
			$repos_format = [];

			foreach ($repos as $key => $rep) {
				if (($rep['private']?'false':'true') != $private)
					$repos_format[] = self::getFormatItem($rep, ['id' => 'id' ,'name' => 'name','description'=>'description', 'private'=>'private', 'language'=>'language']);
			}
			$this->redis->set($find_string, json_encode($repos_format));
		}
		return self::generateJSON("repositories", $repos_format);
	}
}