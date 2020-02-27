<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <script src="https://widget.cloudinary.com/v2.0/global/all.js" type="text/javascript"></script>  
    <link href="{{ mix('main.css', 'vendor/silscaffold') }}" rel="stylesheet" />
    <script>
      var cloudinaryConfig = {
        cloudName: '<?=getenv('CLOUDINARY_CLOUD_NAME')?>',
        uploadPreset: '<?=getenv('CLOUDINARY_UPLOAD_PRESET')?>',
        apiKey: '<?=getenv('CLOUDINARY_API_KEY')?>'
      }
    </script>
    <script src="{{ mix('app.js', 'vendor/silscaffold') }}" defer></script>
  </head>
  <body>
    <div class="bg-gray-200 w-full py-4 px-6 flex">
      <div class="text-2xl font-bold w-1/4">
        {{getenv('APP_NAME')}}
      </div>
      <div class="w-3/4 text-right">
      <?php
        foreach (\Sil\Scaffold\SilScaffold::getScaffolds() as $datatype){
          $scaffold = new \Sil\Scaffold\SilScaffold($datatype['slug']);
          ?>
          <a href="/admin/{{$scaffold->slug}}" class="ml-4 font-semibold">{{$scaffold->display_name_plural}}</a>
          <?php
        }
      ?>
      </div>
    </div>
    <div class="p-8">
        @inertia
    </div>
  </body>
</html>