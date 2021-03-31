// this file changes the value displayed before 'Slug' input field on location form edit. JS is easier than doing it through the server side in this case, since the server displays app('url'), which may have unintended consequences when switching mid run. 
$(document).ready(function(){
    locationdomains_replace_location_domain();
    $('#form-field-location-use-alternate-domain, #form-field-location-alternate-domain').change(function(){locationdomains_replace_location_domain();});
});

function locationdomains_replace_location_domain(){
    if( ! $('.field-permalink .input-group .input-group-prepend .input-group-text').data('original-url')){
        $('.field-permalink .input-group .input-group-prepend .input-group-text').data('original-url', $('.field-permalink .input-group .input-group-prepend .input-group-text').html());
    }
    if($('#form-field-location-use-alternate-domain').prop('checked')){
        $('.field-permalink .input-group .input-group-prepend .input-group-text').html($('#form-field-location-use-alternate-domain, #form-field-location-alternate-domain').val());
    }
    else{
        $('.field-permalink .input-group .input-group-prepend .input-group-text').html($('.field-permalink .input-group .input-group-prepend .input-group-text').data('original-url'));
    }
    
}