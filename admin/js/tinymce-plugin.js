(function() {
    tinymce.PluginManager.add('pre_code_button', function( editor, url ) {
        editor.addButton( 'pre_code_button', {
            text: 'Text',
            icon: "pre_code_button",
            image: 'https://images-na.ssl-images-amazon.com/images/G/01/PAAPI/AmazonAssociatesLinkBuilder/icon._V278877987_.png',
            tooltip: "Amazon Text Link",
            cmd: 'atuljha'
            // onclick: function() {
            // var selected = tinyMCE.activeEditor.selection.getContent();
            // console.log('selected = ' + selected);
            // var content = '<b>' + selected + '</b>';
            // editor.insertContent( content );
            // }
        });

        function getSelectedLink() {
            var href, html,
                node = editor.selection.getNode(),
                link = editor.dom.getParent( node, 'a[href]' );
            return link;
        }

        editor.addCommand('atuljha', function() {
            var node = editor.selection.getNode();
            var link = editor.dom.getParent( node, 'a[href]' );
            console.log("getNode="+node);
            console.log("link="+link);
            var selected = tinyMCE.activeEditor.selection.getContent();
            jQuery('#aalb-admin-input-search').val(selected);
            jQuery('#aalb-admin-button-create-amazon-shortcode').click();
            // var selected = tinyMCE.activeEditor.selection.getContent();
            // console.log('SELECTED = ' + selected);
            // var content = '<b>' + selected + '</b>';
            // editor.insertContent( content );
            // editor.windowManager.open({
            //     title: 'Example plugin',
            //     url:'/wordpress/wp-content/plugins/amazon-associates-link-builder/admin/partials/aalb_meta_box.php',
                // body: [
                //   {type: 'textbox', name: 'title', label: 'Title'}
                // ],
                // onsubmit: function(e) {
                //     // Insert content when the window form is submitted
                //     editor.insertContent('Title: ' + e.data.title);
                // }
            //});
        });
    });
})();


// tinymce.init({
//   selector: 'textarea',
//   height: 500,
//   toolbar: 'pre_code_button',
//   menubar: false,
//   setup: function (editor) {
//     editor.addButton('pre_code_button', {
//       text: 'My button',
//       icon: false,
//       onclick: function () {
//         editor.insertContent('&nbsp;<b>It\'s my button!</b>&nbsp;');
//       }
//     });
//   },
//   ,
//   content_css: [
//     '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
//     '//www.tinymce.com/css/codepen.min.css'
//   ]
// });