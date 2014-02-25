<?php

namespace My\Model\Note;

use Craft\Storage\File;
use Craft\Text\Regex;
use My\Model\Note;

trait Provider
{

    /**
     * Get all notes
     * @return Note[]
     */
    public static function all()
    {
        // init
        $notes = [];

        // find in folder
        foreach(glob(NB_ROOT . '/*.html') as $file) {
            $file = pathinfo($file, PATHINFO_FILENAME);
            if($note = static::one($file)) {
                $notes[] = $note;
            }
        }

        return $notes;
    }


    /**
     * Get one note
     * @param $id
     * @return Note
     */
    public static function one($id)
    {
        // get note model
        $note = new Note();
        $note->id = $id;

        // resolve filename
        $filename = NB_ROOT . $note->id . '.html';

        // does not exists
        if(!File::exists($filename)) {
            return false;
        }

        // get content
        $note->content = File::read($filename);

        // get date
        $note->date = filemtime($filename);

        // parse title
        if($data = Regex::match($note->content, '/<h[0-9]>(.+)<\/h[0-9]>/')) {
            $note->title = array_shift($data);
        }
        else {
            $note->title = 'No title';
        }

        return $note;
    }


    /**
     * Save note
     * @param Note $note
     * @return bool
     */
    public static function save(Note $note)
    {
        // create
        if(!$note->id) {
            $note->id = time() . uniqid();
        }

        // resolve filename
        $filename = NB_ROOT . $note->id . '.html';

        // write file
        File::write($filename, $note->content);

        return $note->id;
    }

    /**
     * Drop note
     * @param $id
     * @return bool
     */
    public static function drop($id)
    {
        // resolve filename
        $filename = NB_ROOT . $id . '.html';

        if(File::exists($filename)) {
            return File::delete($filename);
        }

        return false;
    }

} 