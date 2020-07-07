( function( blocks, element ) {
    var el = element.createElement;

    blocks.registerBlockType( 'tkt-gutenberg-blocks/layout-part-body', {
        title: 'Layout Part: Body',
        icon: 'layout',
        category: 'layout',

        
        example: {},

        edit: function( props ) {

            return el(
                'p',
                { className: props.className },
                'Here your Posts Main Content Will Display. You can design the Post Contents with Content Templates.'
            );
        },
        save: function( props) {
            return el(
                'div',
                { className: props.className },
                '%%POST_CONTENT%%'
            );
        },
    } );
}(
    window.wp.blocks,
    window.wp.element
) );

