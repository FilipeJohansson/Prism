<?php

    class Music {
    
        public function __construct($id, $videoId, $title, $lyric, $times) {
            $this->id = $id;
            $this->videoId = $videoId;
            $this->title = $title;
            $this->lyric = $lyric;
            $this->times = $times;
        }

        public function __set($name, $value) {
            $this->$name = $value;
        }

        public function __get($name) {
            if (property_exists($this, $name)) {
                return $this->$name;
            }
        }

        public function __isset($name) {
            return isset($this->data[$name]);
        }

        public function __unset($name) {
            unset($this->data[$name]);
        }

    }