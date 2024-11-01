jQuery(document).ready(function($) {

	//Translate Close Popup
	$( document ).on( 'click', '.wwt-tran-modal-close,.wwt-tran-modal-overly', function(e) {
		$('.wwt-tran-msg').hide();
		$('#wwt-tran-seo-text').val('');
		$('.wwt-tran-modal-overly').hide();
		$('.wwt-tran-modal').hide();
		$('#wwt-tran-google-tran').attr('checked', false);
	});

	//Question Type
	$( document ).on( 'click', '.wwt-tran-seo-advise', function() {

		var type = $('.wwt-tran-seo-advise:checked').val();
			
		if( type == 'General Advice' ) {
			$('.wwt-tran-question-section').hide();
		} else{
			$('.wwt-tran-question-section').show();
		}
		
	});

	//Click on translate button
	$( document ).on( 'click', '.wwt-tran-term-btn', function() {

		var this_obj 	    = $(this);
		var term_id 	    = this_obj.attr('data-id');
		var btn_type 	    = this_obj.attr('data-btn_type');

		this_obj.attr('disabled', 'disabled');
		this_obj.after('<div class="wwt-tran-loading"></div>');

		var data = {
			action 	 		  : 'wwt_tran_translate_buttons_terms',
			term_id  		  :	term_id,
			btn_type  		  :	btn_type,
		};

		$.post( ajaxurl,data, function( response ) {

			var response = JSON.parse( response );

			if( response.sucess == 1 ) {
				$('.wp-tran-sucess').remove();
				this_obj.after('<p class="wp-tran-sucess">'+response.msg+'</p>');
			}
			this_obj.removeAttr('disabled', 'disabled');
			$('.wwt-tran-loading').remove();
		});
	});

	$(document).on('change', 'input:radio[name="professional-form[translate_type]"]', function(){
		if($(this).val() == 'default'){
			$('#wwt_tm_create_project').show();
		} else {
			$('#wwt_tm_create_project').hide();
		}
	});

	//Click on Checkout button
	$( document ).on( 'click', '#wwt_create_checkout', function() {

		var this_obj 	    = $(this);
		if( WWT_Tran.current_screen == 'product' ) {
			var product_name  = $('#title').val();
			var product_id 	  = $('#post_ID').val();
		} else {
			var product_name = $('#name').val();
			var product_id 	 = $("input[name=tag_ID]").val();
		}

		var product_price      = $('#wwt-tran-pro-total-price').val();
		var shop_name 	       = $("#wwt-tran-shop-list option:selected").text();
		var is_shrt_desc_check = $('input[name="professional-form[short_description]"]:checked').length;
		var full_or_part_text  = $('input[name="professional-form[text_type]"]:checked').next().html();
		var full_or_part_val   = $('input[name="professional-form[text_type]"]:checked').val();
		var remark 			   = $("#remark-popup").val();

		if(full_or_part_val == 'part'){
			var description = $("#wwt-selected-content").val();
		} else {
			var description = $("#wwt-selection-para").html();
		}

		this_obj.attr('disabled', 'disabled');
		$('#wwt-tran-pro-modal').find('.wwt-tran-loading').show();

		var data = {
				action 			: 'wwt_tran_checkout_setup',
				yws_path		: 1,
		        address			: WWT_Tran.HOSTNAME,
		        createproduct	: product_name,
		        productprice	: product_price,
		        product_id		: product_id,
		        shop_name		: shop_name,
		        is_shrt_desc_check : is_shrt_desc_check,
		        full_or_part_text  : full_or_part_text,
		        full_or_part_val   : full_or_part_val,
		        remark			   : remark,
		        description		   : description,
		        site_url		   : WWT_Tran.site_url,
		        type			   : WWT_Tran.current_screen
			};

		$.post( ajaxurl,data, function( response ) {

			var response = JSON.parse( response );
			if( response.sucess == 1 ) {
				$('#wwt-tran-pro-modal').find('.wwt-tran-msg').removeClass('wp-tran-error');
				$('#wwt-tran-pro-modal').find('.wwt-tran-msg').addClass('wp-tran-sucess');
				$('#wwt-tran-pro-modal').find('.wwt-tran-msg').html(response.msg);
				$('#wwt-tran-pro-modal').find('.wwt-tran-msg').show();
				window.location.replace(WWT_Tran.HOSTNAME+"?wwt-add-to-cart="+response.post_id); //Redirect URL to other domain
			}else{
				$('#wwt-tran-pro-modal').find('.wwt-tran-msg').removeClass('wp-tran-sucess');
				$('#wwt-tran-pro-modal').find('.wwt-tran-msg').addClass('wp-tran-error');
				$('#wwt-tran-pro-modal').find('.wwt-tran-msg').html(response.error);
				$('#wwt-tran-pro-modal').find('.wwt-tran-msg').show();
			}
		});
		this_obj.removeAttr('disabled', 'disabled');
		$('#wwt-tran-pro-modal').find('.wwt-tran-loading').hide();
	});

	$( document ).on( 'click', '.wwt-tran-seo-pro-btn', function() {
		$('.wwt-tran-modal-overly').show();
		$('#wwt-tran-seo-pro-modal').show();
	});

	//Click on translate button
	$( document ).on( 'click', '.wwt-tran-send-seo-pro-btn', function() {

		var this_obj 	    = $(this);
		var seo_texts	    = $('#wwt-tran-seo-text').val();
		var spend_time  	= $('#wwt-tran-seo-spend-time').val();
		var per_word_price  = $('#wwt-tran-seo-word-price').val();
		var post_id         = $('#wwt-tran-post-id').val();
		var total_price     = $('#wwt-tran-seo-total-price').val();
		var seo_advise      = $('.wwt-tran-seo-advise:checked').val();
		var type            = 'seo'

		this_obj.attr('disabled', 'disabled');
		$('#wwt-tran-seo-pro-modal').find('.wwt-tran-loading').show();

		var data = {
			action 	 		  : 'wwt_tran_translate_buttons_admin',
			post_id  		  :	post_id,
			btn_type  		  :	type,
			seo_texts		  : seo_texts,
			seo_advise		  : seo_advise,
			spend_time 	  	  :	spend_time,
			per_word_price    :	per_word_price,
			total_price       :	total_price,
		};

		$.post( WWT_Tran.ajaxurl,data, function( response ) {

			var response = JSON.parse( response );

			if( response.sucess == 1 ) {
				$('#wwt-tran-seo-pro-modal').find('.wwt-tran-msg').addClass('wp-tran-sucess');
				$('#wwt-tran-seo-pro-modal').find('.wwt-tran-msg').html(response.msg);
				$('#wwt-tran-seo-pro-modal').find('.wwt-tran-msg').show();
				window.location.replace(WWT_Tran.HOSTNAME+"?wwt-add-to-cart="+response.post_id); //Redirect URL to other domain
			}else if (response.error == 1){
				$('#wwt-tran-seo-pro-modal').find('.wwt-tran-msg').removeClass('wp-tran-sucess');
				$('#wwt-tran-seo-pro-modal').find('.wwt-tran-msg').addClass('wp-tran-error');
				$('#wwt-tran-seo-pro-modal').find('.wwt-tran-msg').html(response.msg);
				$('#wwt-tran-seo-pro-modal').find('.wwt-tran-msg').show();
			}

			this_obj.removeAttr('disabled', 'disabled');
			$('#wwt-tran-seo-pro-modal').find('.wwt-tran-loading').hide();
		});

	});

	//Professional Button Process
	$( document ).on( 'click', '.wwt-tran-seo-pro-term-btn', function() {
		$('.wwt-tran-modal-overly').show();
		$('#wwt-tran-term-seo-pro-modal').show();
	});

	//Click on translate button
	$( document ).on( 'click', '.wwt-tran-send-seo-pro-term-btn', function() {

		var this_obj 	    = $(this);
		var seo_texts	    = $('#wwt-tran-seo-text').val();
		var spend_time  	= $('#wwt-tran-seo-spend-time').val();
		var per_word_price  = $('#wwt-tran-seo-word-price').val();
		var term_id         = $('#wwt-tran-term-id').val();
		var total_price     = $('#wwt-tran-seo-total-price').val();
		var seo_advise      = $('.wwt-tran-seo-advise:checked').val();
		var type            = 'seo'

		this_obj.attr('disabled', 'disabled');
		$('#wwt-tran-term-seo-pro-modal').find('.wwt-tran-loading').show();

		var data = {
			action 	 		  : 'wwt_tran_translate_buttons_terms',
			term_id  		  :	term_id,
			btn_type  		  :	type,
			seo_texts		  : seo_texts,
			spend_time 	  	  :	spend_time,
			seo_advise		  : seo_advise,
			per_word_price    :	per_word_price,
			
			total_price       :	total_price,
		};

		$.post( WWT_Tran.ajaxurl,data, function( response ) {

			var response = JSON.parse( response );

			if( response.sucess == 1 ) {
				$('#wwt-tran-term-seo-pro-modal').find('.wwt-tran-msg').addClass('wp-tran-sucess');
				$('#wwt-tran-term-seo-pro-modal').find('.wwt-tran-msg').html(response.msg);
				$('#wwt-tran-term-seo-pro-modal').find('.wwt-tran-msg').show();
				window.location.replace(WWT_Tran.HOSTNAME+"?wwt-add-to-cart="+response.post_id); //Redirect URL to other domain
			}else if (response.error == 1){
				$('#wwt-tran-term-seo-pro-modal').find('.wwt-tran-msg').removeClass('wp-tran-sucess');
				$('#wwt-tran-term-seo-pro-modal').find('.wwt-tran-msg').addClass('wp-tran-error');
				$('#wwt-tran-term-seo-pro-modal').find('.wwt-tran-msg').html(response.msg);
				$('#wwt-tran-term-seo-pro-modal').find('.wwt-tran-msg').show();
			}

			this_obj.removeAttr('disabled', 'disabled');
			$('#wwt-tran-term-seo-pro-modal').find('.wwt-tran-loading').hide();
		});

	});

	$( document ).on( 'change', '#wwt-tran-seo-spend-time', function() {
		var spend_time = $(this).val();
		var price      = $('#wwt-tran-seo-word-price').val();

		var total_price = spend_time * price;
		var total_round = Math.round( total_price * 100) / 100;
		$('#wwt-tran-seo-total-price').val(total_round);
		$('#wwt-tran-spend-time-display').html(spend_time + ' Minutes');
	});

	$( document ).on( 'change', '#wwt-tran-price-options', function() {

		var price_option = $(this).val();

		if( price_option == 'general' ) {
			$('.wwt-tran-product-section').hide();
			$('.wwt-tran-product-cat-section').hide();
		} else if( price_option == 'product' ) {
			$('.wwt-tran-product-section').show();
			$('.wwt-tran-product-cat-section').hide();
		} else if( price_option == 'category' ) {
			$('.wwt-tran-product-section').hide();
			$('.wwt-tran-product-cat-section').show();
		}

	});

	$( document ).on( 'click', '#wwt_tran_adjust_price_save', function() {

		var flat_price 	  = $('#wwt-tran-product-price-in-flat').val();
		var percent_price = $('#wwt-tran-product-price-in-percent').val();

		if( flat_price == "" && percent_price == "" ) {
			alert(WWT_Tran.price_msg);
			return false;
		}
		return true;
	});

	$( document ).on( 'click', '.wwt-tran-ajax-btn', function() {

		var this_obj 	    = $(this);
		var popup_type 	    = $(this).attr('data-popup');
		var popup_id 	    = $(this).attr('data-id');

		$('.wwt-tran-spinner').addClass('is-active');

		var data = {
			action 	 		  : 'wwt_tran_build_translate_popup',
			popup_type  	  :	popup_type,
			popup_id  		  :	popup_id,
		};

		$.post( WWT_Tran.ajaxurl,data, function( response ) {

			var response = JSON.parse( response );

			if( response.sucess == 1 ) {

				$('#wwt-tran-pro-modal').html(response.html);
				$('.wwt-tran-modal-overly').show();
				$('#wwt-tran-pro-modal').show();
			}
		
			$('.wwt-tran-spinner').removeClass('is-active');
		});
	});

	$( document ).on( 'click', '.wwt-tran-pro-form-submit, .wwt-google-tran-replace', function() {

		var form_data = $("#wwt-tran-pro-form").serialize();
		var this_obj  = $(this);
		var	tran_api  = this_obj.attr('data-google');

		this_obj.attr('disabled', 'disabled');
		$('#wwt-tran-pro-modal').find('.wwt-tran-loading').show();

		var data = {
			action 	: 'wwt_tran_professional_form_submit',
			form_data : form_data,
			tran_api  : tran_api,
		};

		$.post( WWT_Tran.ajaxurl,data, function( response ) {

			var response = JSON.parse( response );

			if( response.sucess == 1 ) {

				$('#wwt-tran-pro-modal').find('.wwt-tran-msg').addClass('wp-tran-sucess');
				$('#wwt-tran-pro-modal').find('.wwt-tran-msg').html(response.msg);
				$('#wwt-tran-pro-modal').find('.wwt-tran-msg').show();
			}else if( response.google_sucess == 1 ) {
				$('#wwt-tran-pro-modal').find('#google-tran-desc').val(response.text);
				$('#wwt-tran-pro-modal').find('.wwt-google-trna-title').html(response.title);
			}

			this_obj.removeAttr('disabled', 'disabled');
			$('#wwt-tran-pro-modal').find('.wwt-tran-loading').hide();
		});	
	});

	$( document ).on( 'click', '.wwt-translate-type', function() {

		if( $(this).is(':checked') && $(this).val() == 'own' ) {
			$('.wwt-own-wrap').show();
			$('.wwt-short-desc-section').hide();
			$('.wwt-tran-text-type-section').hide();
			$('.wwt-tran-paragraph').hide();
			$('.wwt-cost-wrap').hide();
			$('.wwt-tran-remark-wrap').hide();
			$('.wwt-google-tran-wrap').hide();
			$('a.wwt-tran-pro-form-submit').text(WWT_Tran.pro_own_btn_text);

		}else if( $(this).is(':checked') && $(this).val() == 'google' ) {
			$('.wwt-own-wrap').hide();
			$('.wwt-google-tran-wrap').show();
			$('.wwt-cost-wrap').hide();
			$('.wwt-tran-remark-wrap').show();

			if( WWT_Tran.api_valid !="" ) {
				$('.wwt-tran-text-type-section').hide();
				$('.wwt-tran-paragraph').hide();
				$('.wwt-short-desc-section').hide();
			}else{
				$('.wwt-tran-text-type-section').show();
				$('.wwt-short-desc-section').show();

				if($('.wwt-text-type').is(':checked') && $('.wwt-text-type:checked').val() == 'part' ) {
					$('.wwt-tran-paragraph').hide();
					$('.wwt-short-desc-section').hide();
				}else{
					$('.wwt-short-desc-section').show();
				}
			}

		}else if( $(this).is(':checked') && $(this).val() == 'disable_product' ) {
			$('.wwt-own-wrap').hide();
			$('.wwt-short-desc-section').hide();
			$('.wwt-tran-text-type-section').hide();
			$('.wwt-tran-paragraph').hide();
			$('.wwt-cost-wrap').hide();
			$('.wwt-tran-remark-wrap').hide();
			$('.wwt-google-tran-wrap').hide();
			$('a.wwt-tran-pro-form-submit').text(WWT_Tran.disable_pro_btn_text);

		} else{
			$('.wwt-own-wrap').hide();
			$('.wwt-google-tran-wrap').hide();
			$('.wwt-cost-wrap').show();
			$('.wwt-tran-remark-wrap').show();
			$('.wwt-tran-text-type-section').show();
			$('a.wwt-tran-pro-form-submit').text(WWT_Tran.pro_inquiry_btn_text);
			if($('.wwt-text-type').is(':checked') && $('.wwt-text-type:checked').val() == 'part' ) {
				$('.wwt-tran-paragraph').show();
				$('.wwt-short-desc-section').hide();
			}else{
				$('.wwt-short-desc-section').show();
			}
		}
	});

	//click on Add More FAQ button
	$(document).on('click', '.wwt-add-more-field', function() {
		var count =	jQuery( 'div.wwt-shop-field-wrap' ).length;
		//Add More Icon
		wwt_add_more_field();
	});

	//click on Remove ICON create button
	$(document).on('click', '.wwt-shop-name-remove', function() {
	
		var shop_box	= $(this).parent();
		var shop_name_count =	jQuery( 'div.wwt-shop-field-wrap' ).length;
		if( shop_name_count > 1 ) {//check if FAQ 1
			shop_box.remove();//remove FAQ
		}
	});

	$( document ).on( 'click', '#wwt-tran-include-short-description', function() {

		if( $(this).is(':checked') ) {
			var per_word_price 	  = $('#wwt-tran-pro-per-word-price').val();
			var total_word    	  = $('#wwt-tran-pro-total-words-desc').val();

			total_word 	 = parseInt(total_word);
			total_price	 = total_word * per_word_price;
		var total_round  = Math.round( total_price * 100) / 100;
			total_round = total_round  =  total_round > 0.50 ? total_round : 0.50;
			$('.wwt-no-word-label').html(total_word);
			$('#wwt-tran-pro-total-words').val(total_word);
			$('#wwt-tran-pro-total-price').val(total_round);
		}else{
			var per_word_price 	  = $('#wwt-tran-pro-per-word-price').val();
			var total_word    	  = $('#wwt-tran-pro-total-words-desc').val();
			var short_descr_words = $('#wwt_short_descr_words').val();

			total_word 	 = parseInt(total_word) - parseInt(short_descr_words);
			total_price	 = total_word * per_word_price;
		var total_round  = Math.round( total_price * 100) / 100;
			total_round = total_round > 0.50 ? total_round : 0.50;
			$('.wwt-no-word-label').html(total_word);
			$('#wwt-tran-pro-total-words').val(total_word);
			$('#wwt-tran-pro-total-price').val(total_round);
		}
	});

	//click on Full text or Partial
	$(document).on('click', '.wwt-text-type', function() {
		if( $(this).is(':checked') && $(this).val() == 'part' ) {
			$('.wwt-tran-paragraph').show();
			$('.wwt-short-desc-section').hide();
		}else{
			$('.wwt-tran-paragraph').hide();
			$('.wwt-short-desc-section').show();

			var per_word_price 	  = $('#wwt-tran-pro-per-word-price').val();
			var total_word    	  = $('#wwt-tran-pro-total-words-desc').val();

			total_price		 = parseInt(total_word) * per_word_price;
			var total_round  = Math.round( total_price * 100) / 100;
			total_round = total_round > 0.50 ? total_round : 0.50;
			$('#wwt-tran-include-short-description').prop('checked', true);

			$('.wwt-no-word-label').html(total_word);
			$('#wwt-tran-pro-total-words').val(total_word);
			$('#wwt-tran-pro-total-price').val(total_round);
		}
	});

	//Mouse selected text get
	jQuery(document).on('mouseup','#wwt-selection-para', function() {

   		if (this.selectionStart != this.selectionEnd){ // check the user has selected some text inside field
	        var selectedtext = this.value.substring(this.selectionStart, this.selectionEnd)
	        $(this).siblings( '#wwt-selected-content' ).val( selectedtext );
	        $(this).siblings( '.wwt-display-selected-cont' ).html( selectedtext );

	        var wordsCount = selectedtext != '' ? selectedtext.split(' ').length : 0;

	        var per_word_price 	  = $('#wwt-tran-pro-per-word-price').val();

			total_price		 = wordsCount * per_word_price;
			var total_round  = Math.round( total_price * 100) / 100;
			total_round = total_round > 0.50 ? total_round : 0.50;
			$('.wwt-no-word-label').html(wordsCount);
			$('#wwt-tran-pro-total-words').val(wordsCount);
			$('#wwt-tran-pro-total-price').val(total_round);
	    }
	});

	//Own translatin calcu
	jQuery(document).on('keyup','#own-translation-full-desc', function() {

		var full_desc_words  = this.value;
		var short_desc_words = $('#own-translation-short-desc').val();
			full_desc_words  = full_desc_words != ''? full_desc_words.match(/\S+/g).length : 0;
			short_desc_words = short_desc_words != ''? short_desc_words.match(/\S+/g).length : 0;

		var total_words      = parseInt(full_desc_words) + parseInt(short_desc_words);
	    var per_word_price 	 = $('#wwt-tran-pro-per-word-price').val();

		total_price		 = total_words * per_word_price;
		var total_round  = Math.round( total_price * 100) / 100;
		total_round = total_round > 0.50 ? total_round : 0.50;
		$('.wwt-no-word-label').html(total_words);
		$('#wwt-tran-pro-total-words').val(total_words);
		$('#wwt-tran-pro-total-price').val(total_round);
	});

	//Own translatin calcu
	jQuery(document).on('keyup','#own-translation-short-desc', function() {

		var short_desc_words = this.value;
		var full_desc_words  = $('#own-translation-full-desc').val();
			short_desc_words  = short_desc_words != '' ? short_desc_words.match(/\S+/g).length : 0;
			full_desc_words = full_desc_words != ''? full_desc_words.match(/\S+/g).length : 0;

		var total_words      = parseInt(full_desc_words) + parseInt(short_desc_words);
	    var per_word_price 	 = $('#wwt-tran-pro-per-word-price').val();

		total_price		 = total_words * per_word_price;
		var total_round  = Math.round( total_price * 100) / 100;
		total_round = total_round > 0.50 ? total_round : 0.50;
		$('.wwt-no-word-label').html(total_words);
		$('#wwt-tran-pro-total-words').val(total_words);
		$('#wwt-tran-pro-total-price').val(total_round);
	});

	//Create New Project on Text Master Setup
	$( document ).on( 'click', '#wwt_tm_create_project', function() {

		var form_data = $("#wwt-tran-pro-form").serialize();
		var this_obj  = $(this);

		this_obj.attr('disabled', 'disabled');
		$('#wwt-tran-pro-modal').find('.wwt-tran-loading').show();

		var data = {
			action 	: 'wwt_TM_setup_new_project',
			form_data : form_data,
		};

		$.post( WWT_Tran.ajaxurl,data, function( response ) {

			var response = JSON.parse( response );
			if( response.sucess == 1 ) {
				$('#wwt-tran-pro-modal').find('.wwt-tran-msg').removeClass('wp-tran-error');
				$('#wwt-tran-pro-modal').find('.wwt-tran-msg').addClass('wp-tran-sucess');
				$('#wwt-tran-pro-modal').find('.wwt-tran-msg').html(response.msg);
				$('#wwt-tran-pro-modal').find('.wwt-tran-msg').show();
				window.location.replace(WWT_Tran.HOSTNAME+"?wwt-add-to-cart="+response.post_id); //Redirect URL to other domain
			}else{
				$('#wwt-tran-pro-modal').find('.wwt-tran-msg').removeClass('wp-tran-sucess');
				$('#wwt-tran-pro-modal').find('.wwt-tran-msg').addClass('wp-tran-error');
				$('#wwt-tran-pro-modal').find('.wwt-tran-msg').html(response.error);
				$('#wwt-tran-pro-modal').find('.wwt-tran-msg').show();
			}

			this_obj.removeAttr('disabled', 'disabled');
			$( '#wwt-tran-pro-modal' ).find('.wwt-tran-loading').hide();
		});
	});

	jQuery( document ).on( 'change', '#bulk-action-selector-top', function($) {

		var selector	= jQuery( this ).val();

		/*if( selector == 'checkout_translation' ) {

			var checkshop	= jQuery('.actions.bulkactions' ).find('.wwt-checkout-translation').length;

			if( checkshop < 1 ) {

				var shops = '<select class="wwt-checkout-translation" name="Shop_List_options">'+WWT_Tran.Shop_List_options+'</select>';
				jQuery( this ).after( shops );
			}

		}*/
	});

	if( WWT_Tran.current_screen == 'edit-product' ) {

		var checkshop	= jQuery('.actions.bulkactions' ).find('.wwt-checkout-translation').length;

		if( checkshop < 1 ) {
			
			var shops = '<select class="wwt-checkout-translation" name="Shop_List_options">'+WWT_Tran.Shop_List_options+'</select>';
			var html  = '<div class="alignleft actions">';
				html  += shops;
				html  += '<input id="shop-translate-action" name="Shop-Apply" class="button action" value="Apply Translate" type="submit">';
				html  += '</div">';

			jQuery('.actions.bulkactions').after( html );

		}
	}

	jQuery( document ).on( 'click', '#shop-translate-action', function($) {
		//var selector = jQuery('#bulk-action-selector-top').val();

		//if( selector == 'checkout_translation' ) {
			jQuery('.wwt-tran-modal-overly').show();
			jQuery('#wwt-tran-waiting-modal').show();
		//}
	});
});

//function for add more
function wwt_add_more_field() {

	var id_val	 	  =	jQuery( 'div.wwt-shop-field-wrap:last' ).attr( 'id' );//Get Id value
	var id_value 	  = id_val.split('_');
	var field_name_wrap = '';

	var count	 =	parseInt( id_value[1] ) + 1;
	
	var dupd_faq =  jQuery('div.wwt-shop-field-wrap:last').clone().attr('id', 'wwt-shop-field-wrap_'+ count);

	//Get icn for Wraper
	field_name_wrap	= dupd_faq.find( '.wwt-shop-field-wrap' );

	//Change all FAQ for html index
	dupd_faq.find( '.wwt-shop-name' ).attr( 'id', 'wwt-shop-name-'+ count ).attr( 'name', 'wwt_tran_options[shop_list]['+count+'][name]' );
	dupd_faq.find( '.wwt-shop-lang' ).attr( 'id', 'wwt-shop-lang-'+ count ).attr( 'name', 'wwt_tran_options[shop_list]['+count+'][lang]' );
	dupd_faq.find( '.wwt-shop-url' ).attr( 'id', 'wwt-shop-url-'+ count ).attr( 'name', 'wwt_tran_options[shop_list]['+count+'][url]' );

	dupd_faq.insertAfter('div.wwt-shop-field-wrap:last');
	jQuery('div.wwt-shop-field-wrap:last').find( '.wwt-shop-name' ).val('');
	jQuery('div.wwt-shop-field-wrap:last').find( '.wwt-shop-url' ).val('');
	jQuery('div.wwt-shop-field-wrap:last').find( '.wwt-shop-name' ).removeAttr('readonly');

}