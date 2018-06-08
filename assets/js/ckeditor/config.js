CKEDITOR.editorConfig = function( config ) {
  config.toolbarGroups = [
    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
    { name: 'colors', groups: [ 'colors' ] },
    { name: 'links', groups: [ 'links' ] },
    { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
    { name: 'insert', groups: [ 'insert' ] },
    { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
    { name: 'forms', groups: [ 'forms' ] },
    { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
    { name: 'styles', groups: [ 'styles' ] },
    { name: 'tools', groups: [ 'tools' ] },
    { name: 'document', groups: [ 'document', 'mode', 'doctools' ] },
    { name: 'others', groups: [ 'others' ] },
    { name: 'about', groups: [ 'about' ] }
  ];

  config.language = 'de';

  config.removeButtons = 'Form,Checkbox,Radio,TextField,Textarea,Select,ImageButton,HiddenField,Button,Templates,Preview,Print,NewPage,Save,Cut,Copy,Paste,PasteText,PasteFromWord,Replace,Scayt,Flash,CreateDiv,BidiRtl,BidiLtr,Language,About';
};