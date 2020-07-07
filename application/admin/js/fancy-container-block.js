( function( blocks, editor, element, blockEditor ) {
    var el = element.createElement;

    var withColors = editor.withColors;
    var PanelColorSettings = editor.PanelColorSettings;
    var getColorClassName = editor.getColorClassName;
    var InspectorControls = editor.InspectorControls;

    var InnerBlocks = blockEditor.InnerBlocks;
 

    const colorSamples = [
        {
            name: 'Coral',
            slug: 'coral',
            color: '#FF7F50'
        },
        {
            name: 'Lavender',
            slug: 'lavender',
            color: '#E6E6FA'
        },
        {
            name: 'White',
            slug: 'white',
            color: '#ffffff'
        }
    ];

    blocks.registerBlockType( 'tkt-gutenberg-blocks/fancy-container-block', {
        
        title: 'Container: Fancy',
        icon: 'art',
        category: 'layout',

        example: {},

        attributes: {
            formColor: { // something
                type: 'string',
            },
            customFormColor: { // customSomething
                type: 'string',
            },
        },

        getEditWrapperProps: function () {
            return {
                "data-align": "wide"
            };
        },
 
        // edit: function( props ) {

        //     return el(
        //         'p',
        //         { className: props.className },
        //         'Here your Posts Main Content Will Display. You can design the Post Contents with Content Templates.'
        //     );
        // },
        // save: function( props) {
        //     return el(
        //         'div',
        //         { className: props.className },
        //         'ANYTHING'
        //     );
        // },

        edit: withColors( 'formColor' )( function( props ) {
            var formClasses = (( props.formColor.class || '' ) + ' ' + props.className ).trim();
 
            // form background color
            var formStyles = {
                backgroundColor: props.formColor.class ? undefined : props.attributes.customFormColor,
                padding: '25px',
            };
 
            return [
                el( InspectorControls, {},
                    el( PanelColorSettings,
                        {
                            title: 'Container Background Color',
                            colorSettings: [
                                {
                                    colors: colorSamples,
                                    value: props.formColor.color,
                                    label: 'Background Color',
                                    onChange: props.setFormColor,
                                }
                            ]
                        },
                    ),
                ),
                el(
                    'div',
                    { className: formClasses, style: formStyles },
                    el( InnerBlocks ),
                ),
            ];

        }),
        save: function( props) {
            var formClass = getColorClassName( 'form-color', props.attributes.formColor );
 
            // if exists, otherwise empty string
            var formClasses = formClass || '';
         
            var formStyles = {
                backgroundColor: formClass ? undefined : props.attributes.customFormColor,
            };

            return (
                el( 
                    'div', 
                    { className: formClasses, style: formStyles },
                    el( InnerBlocks.Content ),
                )
            );
        },
    });
} 
    (
        window.wp.blocks,
        window.wp.editor,
        window.wp.element,
        window.wp.blockEditor
    ) 
);
