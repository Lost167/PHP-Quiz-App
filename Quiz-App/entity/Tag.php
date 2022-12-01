<?php

class Tag  implements JsonSerializable {
    private $tagID;
    private $tagName;
    private $tagCategory;
    
    public function __construct($tagID, $tagName, $tagCategory) {
        $this->tagID = $tagID;
        $this->tagName = $tagName;
        $this->tagCategory = $tagCategory;
    }
    public function getTagID() {
        return $this->tagID;
    }

    public function getTagName() {
        return $this->tagName;
    }

    public function getTagCategory() {
        return $this->tagCategory;
    }

    public function jsonSerialize() {
        return get_object_vars($this);
    }

}
