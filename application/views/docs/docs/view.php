<!doctype html>
<html>
    <head>
        <title>Documentation</title>
        <script type="text/javascript" src="https://scripts.eproximiti.com/jquery/jquery.js"></script>
        <script type="text/javascript" src="https://scripts.eproximiti.com/jquery/jquery-ui.js"></script>
        <style>
            body {
                font-family: "Arial", sans-serif;
            }
            .hidden {
                display:none;
            }
            .sample_response {
                border: none;
                background: #fff;
                width: 100%;
                height: 500px;
            }
            #api_calls {
                margin:0;
                padding:0;
            }
            #api_calls li {
                width: auto;
                margin:2px;
                padding: 3px 10px;
                background: #eee;
                font-size:20px;
                cursor:pointer;
                list-style:none;
                display:inline-block;
            }
            #api_calls li:hover {
                background: #ddd;
            }
        </style>
    </head>
    <body>
        <h2>Documentation</h2>
        <p>
            <strong>Base URL: </strong><span id="base_url"></span><br />
            <strong>Current API Version: </strong><span id="current_version"></span>
        </p>
        
        <ul id="api_calls">
            
        </ul>
        <div id="documentation">
            
        </div>
        
        <div id="doc_data" class="doc_data hidden">
            <p>
                <strong>Description:</strong> <span class="description"></span>
            </p>
            <p>
                <strong>The following fields are requried to create a record:</strong>
                <span class="required"></span>
            </p>
            <p>
                <strong>Location: </strong> <span class="location"></span>
            </p>
            <p>
                <strong>Available Methods: </strong><ul class="available_methods"></ul>
            </p>
            <p>
                <strong>Writable Fields: </strong> <span class="writable_fields"></span>
            </p>
            <p>
                <strong>Custom Methods: </strong>
            </p>
            <ul class="custom_methods">
                
            </ul>
            <p>
                <strong>Sample Object: </strong>                
            </p>
            <textarea disabled class="sample_response"></textarea>
        </div>
        
        <script type="text/javascript">
            $(document).ready(function(){
                $.ajax({
                   url: '/docs/docs',
                   type: 'GET',
                   dataType: 'json',
                   success: function(data) {
                       console.log(data);
                       $('#base_url').html(data.documentation_description.base_url);
                       $('#current_version').html(data.documentation_description.current_api_version);
                       $.each(data.documentation,function(k,d){
                           var available_methods = '';
                            $.each(d.available_methods,function(k,v){
                               available_methods+='<li><strong>'+k+': </strong>'+v+'</li>';
                           });
        
                            var html = $('#doc_data').clone();
                           
                           html.attr('id',d.model_name+'_description');
                           html.find('.available_methods').html(available_methods);
                           html.find('.writable_fields').html(d.writable_fields.join(', '));
                           html.find('.custom_methods').html('Custom Methods');
                           html.find('.sample_response').html(JSON.stringify(d.sample_response,undefined,2));
                           html.find('.description').html(d.description);
                           html.find('.location').html(d.location);
                           $('#api_calls').append('<li id="'+d.model_name+'">'+d.model_name.toUpperCase()+'</li>');
                           html.appendTo('#documentation');
                       });
                   }
                });
                
                $('#api_calls').on('click','li',function(){
                    $('.doc_data').hide();
                    $('#'+$(this).attr('id')+'_description').show();
                });
            });
        </script>
    </body>
</html>