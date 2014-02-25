$(document).ready(function(){

    /**
     * Init medium editor
     * @type {MediumEditor}
     */
    var editor = new MediumEditor('#editor .editable', {
        buttonLabels: 'fontawesome',
        buttons: ['bold', 'italic', 'underline', 'anchor', 'header1', 'header2' ,'header3',
                  'quote', 'unorderedlist', 'image'],
        firstHeader: 'h1',
        secondHeader: 'h2',
        thirdHeader: 'h3',
        fourthHeader: 'h4',
        targetBlank: true,
        placeholder: 'What\'s on your mind today ? :)'
    });

    /**
     * Toggle menu
     */
    var panel = $('#panel');
    $('a[data-toggle-panel]').on('click', function(){
        panel.toggleClass('active');
    });

});