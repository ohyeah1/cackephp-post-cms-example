<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link https://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PostsController extends AppController {
	public $autoLayout = false;
	public $autoRender = false;
	
	public $models = [
		'Post',
		'Image'
	];
	public $uses = [
		'Post',
		'Image'
	];
	public function beforeFilter()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: POST, GET, PUT, PATCH, DELETE, OPTIONS');
		header('Access-Control-Allow-Headers: *');
	}


    private function response($status, $mess, $data = [], $total = null) {
        $res = [
            'status'=> $status,
            'message'=> $mess
        ];
        if (isset($total)) $res['total'] = $total;
        if (!empty($data)) $res['data'] = $data;
        return json_encode($res);
	}
	
	public function display() {

	}
	public function beef() {
		$localIP = date('Y-m-d 00:00:00', strtotime($this->request->query('start_date')));
		return "hehehehe ".$localIP;
	}
	public function index() {
		//var_dump($this->request);
		// $this->set('posts', $this->Post->find('all'));
		$params = $this->request->params;
		$query = $this->request->query;
		
        $limit = $query['limit'] ?? 10;
		$page = $query['page'] ?? 1;
		$fields = ['id','title','body','created'];
		if (empty($params["id"])) {			
			$total = $this->Post->find('count');
			$data = $this->Post->find('all',[
				'fields' => $fields,
				'order' => 'Post.created DESC',
				'limit' => $limit,
				'page' => $page	
			]);
			
			return $this->response(200, 'Success', $data, $total);

		} else {
			$data = $this->Post->find('first',[
				'fields' => $fields,
				// 'joins' => [
				// 	[
				// 		'table' => 'images',
				// 		'alias' => 'Images',
				// 		'type' => 'Inner',
				// 		'conditions' => [
				// 			'User.id = Post.ucid'
				// 		]
				// 	]
				// ],
				"conditions" => [
					"Post.id" => $params["id"]
				]			
				]);
			$data['Post']["images"] = $this->Image->getImagesOfPost($params["id"]);
			return $this->response(200, 'Success', $data);

		}


	}

	public function add() {

        if ($this->request->is('post')) {
			$params = $this->request->params;
			$data =  $this->request->data;
			$jsonData = $this->request->input('json_decode');

			$this->Post->create();
			$newPost = $this->Post->save($jsonData);
            if ($newPost) {
				// var_dump($newPost);
				$postId = $newPost['Post']['id'];
				$jsonData->id = $postId;
				foreach ($jsonData->images as $image) {
					$this->Image->create();
					$imageData["posts_id"] = $postId;
					$imageData["image_url"] = $image;

					$newImage = $this->Image->save($imageData);
				}
				// $jsonData["id"] = $this->Post->id;
                return $this->response(200, 'Success', $jsonData);
            }
			return $this->response(400, 'Failed add post');
        }
	}
	
	public function edit($id = null) {
		$params = $this->request->params;
		$id = $params["id"];	
		if (!$id) {
			// throw new NotFoundException(__('Invalid post'));
			return $this->response(400, 'Invalid post');
		}
	
		$post = $this->Post->findById($id);
		if (!$post) {
			return $this->response(400, 'Invalid post');
		}
	
		if ($this->request->is(array('post', 'put'))) {
			$data =  $this->request->data;
			$jsonData = $this->request->input('json_decode');
			$this->Post->id = $id;
			if ($this->Post->save($jsonData)) {
				return $this->response(200, 'Success', $jsonData);
			}
			$this->Flash->error(__('Unable to update your post.'));
		}
		return $this->response(400, 'Failed edit post');
		
	}

	public function delete() {
		$params = $this->request->params;
		$id = $params["id"];
		if ($this->request->is('get')) {
			return $this->response(400, 'Not allow method');
		}
	
		if ($this->Post->delete($id)) {
			return $this->response(200, 'Success');
		} else {
			return $this->response(400, 'Failed delete post');
		}	
	}
	
	public function uploadImage() {

        if ($this->request->is('post')) {
			// $jsonData = $this->request->input('json_decode');
			$params = $this->request->params;
			// var_dump($params["form"]["files"]);
			$DS = DS;
			$location = SITE_ROOT . DS . 'files' . DS;


			// $frmData = $this->request->data;
			// $frmData = $this->request->data;

			// //Get the data from form
			$image = $params["form"]["files"];

			//Path to store upload image
			$target = $location.basename($image["name"]);	
			// //save data to database
			// $this->Profile->save($this->request->data);
			if ($this->Image->checkExist($params["id"], $image["name"])) {
				return $this->response(401, 'Already in this post');
			}
	
			// //Image store to the img folder
			if (move_uploaded_file($image['tmp_name'], $target)) {
				$posts_id = $params["id"];
				$imageData["image_url"] = 'http://ghtk-testtt.com/files/'.$image["name"];
				$imageData["image_name"] = $image["name"];
				if ($posts_id) {
					$this->Image->create();
						$imageData["posts_id"] = $posts_id;
	
						$newImage = $this->Image->save($imageData);
				}
				return $this->response(200, 'Success', $imageData);
			}
			
		}        
	}
}
