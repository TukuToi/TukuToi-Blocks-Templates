( function() {

    var pluginname      = "Template Settings";
    var pluginslug      = "tkt-tw-blocks-templates";
    var plugin_sbarname = "tkt-blocks-templates-sidebar";

    var registerPlugin  = wp.plugins.registerPlugin;

    var compose         = wp.compose.compose;

    var PluginSidebar               = wp.editPost.PluginSidebar;
    var PluginSidebarMoreMenuItem   = wp.editPost.PluginSidebarMoreMenuItem;

    var el              = wp.element.createElement;
    var Fragment        = wp.element.Fragment;

    var PanelBody       = wp.components.PanelBody;
    var PanelRow        = wp.components.PanelRow;
    var SelectControl   = wp.components.SelectControl;
    var Text =          wp.components.TextControl;

    var withDispatch    = wp.data.withDispatch;
    var withSelect      = wp.data.withSelect;
    var dispatch        = wp.data.dispatch;

    var TktHeaderquery = { 
        status : 'publish', // or [ 'publish', 'draft', 'future' ]
        metaKey: '_tkt_template_part', // filter by metadata
        metaValue: 'header' //
    }
    var TktFooterquery = { 
        status : 'publish', // or [ 'publish', 'draft', 'future' ]
        metaKey: '_tkt_template_part', // filter by metadata
        metaValue: 'footer' //
    }

    const TktSidebarIcon = wp.element.createElement('svg', 
        { 
            viewBox: "0 0 33.21 27.63"
        },
        wp.element.createElement( 'path',
            { 
                d: "M649.16,458.56,641,450.35h0a13.32,13.32,0,1,0,0,16.43h0Zm-18.68,8.26A8.23,8.23,0,1,1,637.29,454l-4.58,4.58,4.6,4.6A8.23,8.23,0,0,1,630.48,466.82Zm7.86-8.29a2.55,2.55,0,1,1,2.55,2.54A2.55,2.55,0,0,1,638.34,458.53Z",
                transform: "translate(-616.66 -444.74)"
            }
        ),
        wp.element.createElement( 'path',
            { 
                d: "M640.86,457.58a1,1,0,1,0,1,1A1,1,0,0,0,640.86,457.58Z",
                transform:"translate(-616.66 -444.74)"
            }
        )
    );
    
    var __ = wp.i18n.__;

const TktTemplateName = compose(
    // withDispatch allows to save the selected post ID into post meta
    withDispatch( function( dispatch, props ) {
        return {
            setMetaValue: function( metaValue ) {
                //wp.data.dispatch('core/editor').editPost({slug: 'my otherr slug'})
                dispatch( 'core/editor' ).editPost(
                    { title: metaValue, slug: metaValue }
                );
            }
        }
    } ),
    // withSelect allows to get posts for our SelectControl and also to get the post meta value
    
    withSelect( function( select, props ) {
        return {
            metaValue: select('core/editor').getEditedPostAttribute('title'),
        }
    } ) )( function( props ) {
 
        return el( Text,
            {
                label: props.title,
                onChange: function( content ) {
                    props.setMetaValue( content );
                },
                value: props.metaValue,
            }
        );
 
    }
 
);

    const TktHeader = compose(
    // withDispatch allows to save the selected post ID into post meta
    withDispatch( function( dispatch, props ) {
        return {
            setMetaValue: function( metaValue ) {
                dispatch( 'core/editor' ).editPost(
                    { meta: { [ props.metaKey ]: metaValue } }
                );
            }
        }
    } ),
    // withSelect allows to get posts for our SelectControl and also to get the post meta value
    withSelect( function( select, props ) {
        return {
            posts: select( 'core' ).getEntityRecords( 'postType', 'tkt_template', TktHeaderquery ),
            metaValue: select( 'core/editor' ).getEditedPostAttribute( 'meta' )[ props.metaKey ],
            TktTemplatePart: select( 'core/editor' ).getEditedPostAttribute( 'meta' )[ '_tkt_template_part' ],
        }
    } ) )( function( props ) {

        if( props.TktTemplatePart != 'main') {
            return null;
        }

        // options for SelectControl
        var options = [];
 
        // if posts found
        if( props.posts ) {
            options.push( { value: 0, label: 'Select..' } );
            props.posts.forEach((post) => { // simple foreach loop
                options.push({value:post.id, label:post.title.rendered});
            });
        } else {
            options.push( { value: 0, label: 'Loading...' } )
        }
 
        return el( SelectControl,
            {
                label: props.title,
                options : options,
                onChange: function( content ) {
                    props.setMetaValue( content );
                },
                value: props.metaValue,
            }
        );
 
    }
 
);

   const TktFooter = compose(
    // withDispatch allows to save the selected post ID into post meta
    withDispatch( function( dispatch, props ) {
        return {
            setMetaValue: function( metaValue ) {
                dispatch( 'core/editor' ).editPost(
                    { meta: { [ props.metaKey ]: metaValue } }
                );
            }
        }
    } ),
    // withSelect allows to get posts for our SelectControl and also to get the post meta value
    withSelect( function( select, props ) {
        return {

            posts: select( 'core' ).getEntityRecords( 'postType', 'tkt_template', TktFooterquery ),
            metaValue: select( 'core/editor' ).getEditedPostAttribute( 'meta' )[ props.metaKey ],
            TktTemplatePart: select( 'core/editor' ).getEditedPostAttribute( 'meta' )[ '_tkt_template_part' ],
        }
    } ) )( function( props ) {
        
        if( props.TktTemplatePart != 'main') {
            return null;
        }
        // options for SelectControl
        var options = [];
 
        // if posts found
        if( props.posts ) {
            options.push( { value: 0, label: 'Select..' } );
            props.posts.forEach((post) => { // simple foreach loop
                options.push({value:post.id, label:post.title.rendered});
            });
        } else {
            options.push( { value: 0, label: 'Loading...' } )
        }
        
        return el( SelectControl,
            {
                label: props.title,
                options : options,
                onChange: function( content ) {
                    props.setMetaValue( content );
                },
                value: props.metaValue,
            }
        );
 
    }
 
);

    const TktTemplatePart = compose(
    // withDispatch allows to save the selected post ID into post meta
    withDispatch( function( dispatch, props ) {
        return {
            setMetaValue: function( metaValue ) {
                dispatch( 'core/editor' ).editPost(
                    { meta: { [ props.metaKey ]: metaValue } }
                );
            }
        }
    } ),
    // withSelect allows to get posts for our SelectControl and also to get the post meta value
    withSelect( function( select, props ) {
        return {
            //posts: select( 'core' ).getEntityRecords( 'postType', 'view-template' ),
            metaValue: select( 'core/editor' ).getEditedPostAttribute( 'meta' )[ props.metaKey ],
        }
    } ) )( function( props ) {
        // options for SelectControl
        var options = [];



        options.push( { value: 0, label: 'Select Template Type..' } );
        options.push( {value:"main", label:"Main"},
            {value:"header", label:"Header"},
            {value:"footer", label:"Footer"});

        return el( SelectControl,
            {
                label: props.title,
                options : options,
                onChange: function( content ) {
                    props.setMetaValue( content );
                },
                value: props.metaValue,
            }
        );
 
    }
 
);

    const TktFooterType = compose(
    // withDispatch allows to save the selected post ID into post meta
    withDispatch( function( dispatch, props ) {
        return {
            setMetaValue: function( metaValue ) {
                dispatch( 'core/editor' ).editPost(
                    { meta: { [ props.metaKey ]: metaValue } }
                );
            }
        }
    } ),
    // withSelect allows to get posts for our SelectControl and also to get the post meta value
    withSelect( function( select, props ) {
        return {
            //posts: select( 'core' ).getEntityRecords( 'postType', 'view-template' ),
            metaValue: select( 'core/editor' ).getEditedPostAttribute( 'meta' )[ props.metaKey ],
            TktTemplatePart: select( 'core/editor' ).getEditedPostAttribute( 'meta' )[ '_tkt_template_part' ],
        }
    } ) )( function( props ) {
        // options for SelectControl
        // 
        if( props.TktTemplatePart != 'footer') {
            return null;
        }
        // if( props.metaValue == 'sticky') {
        //     css = wp.data.select( 'core/editor' ).getBlocks()[0].attributes.style.cssClasses;
        //     if (css.includes('tkt_sticky_footer') ){
        //         return null;
        //     }
        //     css.push( "tkt_sticky_footer" );
        //     wp.data.dispatch( 'core/editor' ).updateBlockAttributes( wp.data.select( 'core/editor' ).getBlocks()[0].clientId, {style:{cssClasses: css}} );
        // }

        var options = [];
        options.push( { value: 0, label: 'Set Footer Type..' } );
        options.push( 
                {value:"sticky", label:"Sticky Footer (Pinned)"},
                {value:"scrolling", label:"Scrolling Footer (Moving)"},
            );

        return el( SelectControl,
            {
                label: props.title,
                options : options,
                onChange: function( content ) {
                    props.setMetaValue( content );
                },
                value: props.metaValue,
            }
        );
 
    }
 
);

const TktHeaderType = compose(
    // withDispatch allows to save the selected post ID into post meta
    withDispatch( function( dispatch, props ) {
        return {
            setMetaValue: function( metaValue ) {
                dispatch( 'core/editor' ).editPost(
                    { meta: { [ props.metaKey ]: metaValue } }
                );
            }
        }
    } ),
    // withSelect allows to get posts for our SelectControl and also to get the post meta value
    withSelect( function( select, props ) {
        return {
            //posts: select( 'core' ).getEntityRecords( 'postType', 'view-template' ),
            metaValue: select( 'core/editor' ).getEditedPostAttribute( 'meta' )[ props.metaKey ],
            TktTemplatePart: select( 'core/editor' ).getEditedPostAttribute( 'meta' )[ '_tkt_template_part' ],
        }
    } ) )( function( props ) {
        // options for SelectControl
        // 
        if( props.TktTemplatePart != 'header') {
            return null;
        }
        // if( props.metaValue == 'sticky') {
        //     css = wp.data.select( 'core/editor' ).getBlocks()[0].attributes.style.cssClasses;
        //     if (css.includes('tkt_sticky_footer') ){
        //         return null;
        //     }
        //     css.push( "tkt_sticky_footer" );
        //     wp.data.dispatch( 'core/editor' ).updateBlockAttributes( wp.data.select( 'core/editor' ).getBlocks()[0].clientId, {style:{cssClasses: css}} );
        // }

        var options = [];
        options.push( { value: 0, label: 'Set Header Type..' } );
        options.push( 
                {value:"sticky", label:"Fixed Header (Pinned)"},
                {value:"scrolling", label:"Scrolling Header (Moving)"},
            );

        return el( SelectControl,
            {
                label: props.title,
                options : options,
                onChange: function( content ) {
                    props.setMetaValue( content );
                },
                value: props.metaValue,
            }
        );
 
    }
 
);

    registerPlugin( 'tkt-blocks-templates-sidebar', {
    render: function() {
        return el( Fragment, {},
            el( PluginSidebarMoreMenuItem,
                {
                    target: plugin_sbarname,
                    icon: TktSidebarIcon,
                },
                pluginname
            ),
            el( PluginSidebar,
                {
                    name: plugin_sbarname,
                    icon: TktSidebarIcon,
                    title: pluginname,
                },
                    el( PanelBody, {},

                        el( TktTemplateName,
                            {
                                title : 'Template Name',
                            }
                        ),
                        el( TktTemplatePart,
                            {
                                metaKey: '_tkt_template_part',
                                title : 'Template Part',
                            }
                        ),
                        el( TktHeader,
                            {
                                metaKey: '_tkt_header',
                                title : 'Header',
                            }
                        ),
                        el( TktFooter,
                            {
                                metaKey: '_tkt_footer',
                                title : 'Footer',
                            }
                        ),
                        el( TktFooterType,
                            {
                                metaKey: '_tkt_footer_type',
                                title : 'Footer Type',
                            }
                        ),
                        el( TktHeaderType,
                            {
                                metaKey: '_tkt_header_type',
                                title : 'Header Type',
                            }
                        ),
                    )
            )
        );
    }
} );
 
} )(window.wp);

