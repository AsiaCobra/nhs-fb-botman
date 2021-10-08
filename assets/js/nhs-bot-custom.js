var beforeToggle = function ($clone, i, self) {
    // console.log(self);
    var $container = self.$container;
    if ($clone.find('input:last').val() == "") {
        $container.css({ border: '1px solid red' });
    } else {
        $container.css({ border: 'none' });
    }
    alert('hell')
}
jQuery(document).ready(function ($) {
    jQuery(window).on('load', function ($) {
        jQuery('.metabox-holder').addClass('js-ready')
        jQuery('fieldset').find('.checkbox').trigger('change');
        jQuery('.list-button-type,.group-list-filter.payload select').each(function () {
            let val = jQuery(this).data('value');
            jQuery(this).val(val).trigger('change');
            // console.log(this)
        })
        jQuery('.list-title').each(function () {
            let parent = jQuery(this).parent().addClass('collapse')
        })
    });

    jQuery('.list-button-type').on('change', function () {
        let parent = $(this).parents('.input-group');
        parent.find(".group-list-filter").addClass('d-none').find('input,select').prop('disabled', true);
        parent.find(`.${this.value}`).toggleClass('d-none').find('input,select').prop('disabled', false);
        // console.log(this.value);
    })
    $(document).on('click','.list-title',function(){
        let parent = $(this).parent();
        parent.toggleClass('collapse');
        // parent.css('height','10px');
        // parent.css('height','00px');
        // parent.siblings().addClass('collapse');
    })
    $('#save_persist_menu').on('click',function(e){
        e.preventDefault();
        // console.log(this);
        $.post(ajaxurl, { 'action':'set_persistmenu'},function(res){
            if(res.result) {
                $('.save_persistmenu .save_result').text('Saved Persistent Menu').addClass('updated')
            }else{
                $('.save_persistmenu .save_result').text(`${res.error.message}. Persistent Menu Not Saved`).addClass('error')
            }
            console.log(res)
        });
    })
    $('.wpsa-url').on('change',function(){
        $(this).prev().attr('src',this.value);
    })
    $('fieldset .checkbox').on('change',function(){
        let $status = $(this).prop('checked'),
            btn_clone = $(this).parent().find('.button-clone,.listen-button'),
            $next = $(this).next();
        if($status === true){
            $next.val('on');
            btn_clone.show().find('input,select').prop('disabled',false);
        }else{
            $next.val('off');
            btn_clone.hide().find('input,select').prop('disabled',true);
        }
        // alert(dd)
        // console.log($status)
        // console.log($(this).next())
    })
    $('.hearing').cloner({
        clonableContainer: '.hearing',
        clonable: '.clonable',
        addButton: '.clonable-button-add',
        closeButton: '.clonable-button-close',
        // incrementName: 'hearing-increment',
        afterToggle : function ($clone, i, self) {
            // console.log($clone);
            // // console.log();
            // console.log(i);
            $clone.toggleClass('collapse');
            let buttons_clone = $clone.find('.button-clone .clonable-increment');
            console.log(buttons_clone)
            buttons_clone.each(function(){
                // console.log(this)
                if ($(this)[0].hasAttribute('name')) {                    
                    var old_val = $(this).attr('name');
                    var new_val = old_val.replace(/-?\d+/g, function (n) { return ++n; });    
                    $(this).attr('name', new_val);
                }

            })
            // var $container = self.$container;
            // if ($clone.find('input:last').val() == "") {
            //     $container.css({ border: '1px solid red' });
            // } else {
            //     $container.css({ border: 'none' });
            // }
            // alert('hell0')
        },
    });
})