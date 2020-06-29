<?php
App::uses('AppModel', 'Model');

class Image extends AppModel {
    public function getImagesOfPost($postId) {
        if (!isset($postId)) return null;
        $data = $this->find('all', array(
            'fields' => ['posts_id', 'image_url','image_name'],
            'conditions' => [
                'posts_id' => $postId
            ]
        ));
        return Hash::extract($data, '{n}.Image');
    }
    
    public function checkExist($postId, $image_name) {
        if (!isset($postId)) return null;
        $data = $this->find('first', array(
            'fields' => ['posts_id', 'image_url','image_name'],
            'conditions' => [
                'posts_id' => $postId,
                'image_name' => $image_name
            ]
        ));
        if (isset($data) && !empty($data)) {
            return true;
        }
        return false;
    }
}