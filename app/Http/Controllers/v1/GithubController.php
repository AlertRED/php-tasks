<?php

namespace App\Http\Controllers\v1;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
		$issues = $this->client->api('issue')->all($userName, $repositoryName);
		$issues_format = [];
		foreach ($issues as $issue)
			$issues_format[] = self::getFormatItem($issue, ['id' => 'id' ,'title' => 'title','number'=>'number', 'state'=>'state']);
		return self::generateJSON("issues", $issues_format);
	}	

	public function getRepositories(RequestRepositories $request, $userName){
		$repos = $this->client->api('user')->repositories($userName);
		$repos_format = [];
		foreach ($repos as $rep)
			$repos_format[] = self::getFormatItem($rep, ['id' => 'id' ,'name' => 'name','description'=>'description', 'private'=>'private', 'language'=>'language']);
		return self::generateJSON("repositories", $repos);
	}	

	public function getIssuesSearch(RequestIssuesSearch $request, $userName){
		
		$params = 'user:'.$userName;
		foreach (['title', 'state', 'number'] as $key)
			$params .= isset($request[$key]) ? sprintf(' %s:%s', $key, $request[$key]):'';
		$issues = $this->client->api('search')->issues($params)['items'];
		
		$filter_issues = [];
		foreach ($issues as $issue){
			 $new_item = self::getFormatItem($issue, ['id' => 'id' ,'title' => 'title','number'=>'number', 'state'=>'state']);
			 $new_item['github_id'] = $issue['user']['id'];
			 $name_rep = array_slice(explode('/', $issue['repository_url']), -1)[0];
			 $new_item['repository_id'] = $this->client->api('search')->repositories($name_rep.' user:'.$issue['user']['login'])['items'][0]['id'];
			 $filter_issues[] = $new_item;
		}
		return self::generateJSON("issues", $filter_issues);
	}	

	public function getRepositoriesSearch(Request $request, $userName){

		$title = $request->input('title','');
		$language = isset($request['language']) ? sprintf(' language:%s', $request['language']):'';
		$private = $request->input('private','');

		$find_string = __FUNCTION__.$userName.$title.$language.$private;
		$repos_format = json_decode($this->redis->get($find_string));
		if (!$repos_format){
			$repos = $this->client->api('search')->repositories($title.' user:'.$userName.$language)['items'];
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