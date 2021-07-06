<?php
    include_once("./Models/Database.php");
    include_once("./Models/Music.php");

    class MusicDAO extends Database {
        public function __construct() { }

        private function __clone() { }

        public function __destruct() {
            foreach ($this as $key => $value) {
                unset($this->key);
            }

            foreach (array_keys(get_defined_vars()) as $var) {
                unset(${"$var"});
            }
            unset($var);
        }

        public function getMusicsTitle() {
            $query = "SELECT title FROM musics;";
            $database = Database::getInstance();
            $result = $database->connection->prepare($query);

            if(!$result->execute())
                return -1;

            if($result->rowCount() > 0) {
                $titles = array();
                while($m = $result->fetch(PDO::FETCH_ASSOC))
                    array_push($titles, $m['title']);
                return $titles;
            }
        }

        public function getMusicFromTitle($title) {
            $query = "SELECT * FROM musics WHERE `title` = :title;";
            $database = Database::getInstance();
            $result = $database->connection->prepare($query);
            $result->bindParam(':title', $title, PDO::PARAM_STR);

            if(!$result->execute())
                return -1;

            if($result->rowCount() > 0)
                while ($m = $result->fetch(PDO::FETCH_ASSOC))
                    $music = new Music($m['id'], $m['videoId'], $m['title'], $m['lyric'], $m['times']);
            return $music;
        }
    }