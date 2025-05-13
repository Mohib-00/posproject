<!DOCTYPE html>
<html lang="en">
  <head>
   @include('adminpages.css')

  </head>
  <body>
    <div class="wrapper">
    @include('adminpages.sidebar')

      <div class="main-panel">
        @include('adminpages.header')

        <div class="container">
        </div>

        @include('adminpages.footer')
      </div>
    </div>

    



    @include('adminpages.js')
    @include('adminpages.ajax')

   
  </body>
</html>
