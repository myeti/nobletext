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


    /**
     * Save
     */
    var save_button = $('a.save');
    var saving = false;
    function save()
    {
        // start
        if(saving) {
            return;
        }
        saving = true;

        // get data
        var content = $('#editor .editable').html();
        var url = $('a.save').attr('href');

        // do query
        $.ajax({
            url: url,
            type: 'POST',
            data: {content: content},
            contentType: 'application/x-www-form-urlencoded'
        }).done(function(){

            // flash
            $('a.save').addClass('active');
            setTimeout(function(){
                $('a.save').removeClass('active')
            }, 2000);

            // end
            saving = false;
        });
    }


    /**
     * Manual and Auto save
     */
    save_button.on('click', function(e){
        save();
        e.preventDefault();
        return false;
    });

    setInterval(function(){
        save_button.click();
    }, 10000);

});