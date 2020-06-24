var _candy_token;
var _candy_page;
var Candy = class Candy {
  test(){
    alert('Hi, World');
  }
  showModal(s){
    $('#' + s).modal('show');
  }
  get(url,callback){
    var data;
    var status;
    $.get(url, function(data, status){
      callback(data,status)
    });
  }
  getToken(){
    $.get(window.location.hostname+'?_candy=token',function(data){
      var result = JSON.parse(JSON.stringify(data));
      _candy_token = result.token;
    });
  }
  token(){
    candy.getToken();
    return _candy_token;
  }
  page(){
    if(_candy_page===undefined){
      var req = new XMLHttpRequest();
      req.open('GET', document.location, false);
      req.send(null);
      var headers = req.getAllResponseHeaders().toLowerCase().split("\r\n");
      headers.forEach(element => _candy_page = ((element.split(': ')[0])=='x-candy-page') ? element.split(': ')[1] : _candy_page);
    }
    return _candy_page;
  }
  form(id,callback,m){
    $(document).on("submit",'#'+id,function(e){
      e.preventDefault();
      $('#'+id+' ._candy_form_info').remove();
      $('#'+id+' ._candy').html('');
      $('#'+id+' ._candy').hide();
      if($('#'+id+' input[type=file]').length > 0){
        console.log('file form');
        var datastring = new FormData();
        $('#'+id+' input').each(function(index){
          if($(this).attr('type')=='file'){
            datastring.append($(this).attr('name'), $(this).prop('files')[0]);
          }else{
            datastring.append($(this).attr('name'), $(this).val());
          }
        });
        datastring.append('token', candy.token());
        var cache = false;
        var contentType = false;
        var processData = false;
      }else{
        var datastring = $("#"+id).serialize()+'&token='+candy.token();
        var cache = true;
        var contentType = "application/x-www-form-urlencoded; charset=UTF-8";
        var processData = true;
      }
      $.ajax({
        type: $("#"+id).attr('method'),
        url: $("#"+id).attr('action'),
        data: datastring,
        dataType: "json",
        contentType: contentType,
        processData: processData,
        cache: cache,
        success: function(data) {
          if(data.success){
            if(m===undefined || m){
              if(data.success.result){
                if ($('#'+id+' ._candy_success').length){
                  $('#'+id+' ._candy_success').show();
                  $('#'+id+' ._candy_success').html(data.success.message);
                }else{
                  $('#'+id).append('<span class="_candy_form_info">'+data.success.message+'</span>');
                }
              }else{
                var errors = data.errors;
                $.each(errors, function(index, value) {
                  if($('#'+id+' ._candy_'+index).length){
                    $('#'+id+' ._candy_'+index).html(value);
                    $('#'+id+' ._candy_'+index).show();
                  }else{
                    $('#'+id+' *[name ="'+index+'"]').after('<span class="_candy_form_info" style="color:'+(data.success.result ? 'green' : 'red')+'">'+value+'</span>');
                  }
                });
              }
            }
            if(callback!==undefined){
              callback(data);
            }
          }
        },
        error: function() {
          alert('Somethings went wrong...');
        }
      });
    });
  }
  loader(element,arr,callback){
    $(document).on('click',element,function(e){
      var url_now = window.location.href;
      var url_go = $(this).attr('href');
      var target = $(this).attr('target');
      var page = url_go;
      if((target==null || target=='_self') && (url_go!='' && url_go.substring(0,11)!='javascript:' && url_go.substring(0,1)!='#') && (!url_go.includes('://') || url_now.split("/")[2]==url_go.split("/")[2])){
        e.preventDefault();
        if(url_go!=url_now){
          window.history.pushState(null, document.title, url_go);
        }
        $.each(arr, function(index, value){
          $.ajax({
            url: url_go,
            type: "GET",
            beforeSend: function(xhr){xhr.setRequestHeader('X-CANDY', 'ajaxload');xhr.setRequestHeader('X-CANDY-LOAD', index);},
            success: function(_data, status, request){
              _candy_page = request.getResponseHeader('x-candy-page');
              $(value).fadeOut(function(){
                $(value).html(_data);
                $(value).fadeIn();
                if(callback!==undefined){
                  callback(candy.page);
                }
              });
            },
            error : function(){
              $(this).unbind('click');
              e.currentTarget.click();
            }
          });
        });
      }
    });
    $(window).on('popstate', function(){
      var url_go = window.location.href;
      if((url_go!='' && url_go.substring(0,11)!='javascript:' && !url_go.includes('#'))){
        $.each(arr, function(index, value){
          $.ajax({
            url: window.location.href,
            type: "GET",
            beforeSend: function(xhr){xhr.setRequestHeader('X-CANDY', 'ajaxload');xhr.setRequestHeader('X-CANDY-LOAD', index);},
            success: function(_data, status, request){
              _candy_page = request.getResponseHeader('x-candy-page');
              $(value).fadeOut(function(){
                $(value).html(_data);
                $(value).fadeIn();
                if(callback!==undefined){
                  callback(candy.page());
                }
              });
            },
            error : function(){
              $(this).unbind('click');
              e.currentTarget.click();
            }
          });
        });
      }
    });
  }
}
var candy = new Candy;
$(function(){
  candy.getToken();
});