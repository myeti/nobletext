<?php

namespace My\Logic;

use Craft\Box\Mog;
use Craft\Storage\File;
use Craft\Web\Event\NotFound;
use My\Model\Note;

/**
 * @auth 1
 */
class Editor
{


    /**
     * Load with default text
     * @render views/editor.write
     * @return array
     */
    public function index()
    {
        // get default content
        $content = File::read(
            Mog::path('/views/note/default.html')
        );

        // get note
        $note = new Note();
        $note->content = $content;

        // get all notes
        $notes = Note::all();

        return compact('notes', 'note');
    }


    /**
     * Create new note
     */
    public function create()
    {
        // get default content
        $content = File::read(
            Mog::path('/views/note/new.html')
        );

        // get note
        $note = new Note();
        $note->content = $content;

        // create note
        $id = Note::save($note);

        // reload
        go('/write', $id);
    }


    /**
     * Editor place
     * @render views/editor.write
     * @param string $id
     * @return array
     * @throws \Craft\Web\Event\NotFound
     */
    public function write($id)
    {
        // get note
        $note = Note::one($id);

        // save
        if($content = post('content')) {
            $note->content = $content;
            Note::save($note);
        }

        // get all notes
        $notes = Note::all();

        return compact('notes', 'note');
    }


    /**
     * Delete note
     */
    public function delete($id)
    {
        Note::drop($id);
        go('/');
    }


    /**
     * Preview note
     * @auth 0
     * @render views/editor.preview
     * @param string $id
     * @return array
     */
    public function preview($id)
    {
        // get note
        $note = Note::one($id);

        // no note
        if(!$note) {

            // get default content
            $content = File::read(
                Mog::path('/views/note/unknown.html')
            );

            // get note
            $note = new Note();
            $note->content = $content;
        }

        return compact('note');
    }


    /**
     * 404 not found
     * @auth 0
     * @render views/editor.preview
     * @return array
     */
    public function lost()
    {
        return $this->preview(null);
    }

} 