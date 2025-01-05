<!DOCTYPE html>
<html lang="es">
  <head>
    <title>Donación | Emancipación Cristiana Afro</title>
    @include('header')
    <link rel="stylesheet" href="/resources/css/menustyle.css">
    <!-- Demo CSS -->
  </head>
  <body>

    @include('navbar')
    <br><br><br><br><br>
    <div class="container"><br>
        <h4>Formulario de donación</h4>
        <hr>
        <p>Apreciado aportante, sírvase escribir los datos solicitados en el formulario para realizar su donación.</p>
        <form action="adddonor" method="post">
          @csrf
          <!-- 2 column grid layout with text inputs for the first and last names -->
          <div class="row mb-4">
            <div class="col-6">
              <div class="form-outline">
              <label class="form-label" for="form6Example1">Nombre completo</label>
                <input name="name" type="text" id="name" class="form-control" required/>
              </div>
            </div>
            <div class="col-6">
              <div class="form-outline">
                <label class="form-label" for="form6Example6">Monto a donar</label>
                <input name="donate" type="number" id="donar" class="form-control" required/>
              </div>
            </div>
            <div class="col-6">
              <div class="form-outline">
                <label class="form-label" for="form6Example2">Email</label>
                <input name="email" type="email" id="email" class="form-control" required/>
              </div>
            </div>
          <!-- Text input -->
          <div class="col-6">
            <div class="form-outline mb-4">
              <label class="form-label" for="form6Example3">Dirección</label>
              <input name="address" type="text" id="address" class="form-control" />
            </div>
          </div>

          <!-- Number input -->
          <div class="col-6">
            <div class="form-outline mb-4">
              <label class="form-label" for="form6Example6">Celular</label>
              <input name="phone" type="number" id="phone" class="form-control" />
            </div>
          </div>
          <!-- Message input -->
          <div class="col-6">
            <div class="form-outline mb-4">
              <label class="form-label" for="form6Example7">¿Desea enviar algún mensaje?</label>
              <textarea class="form-control" id="message" name="message" rows="2"></textarea>
            </div>
          </div>
              <input name="merchantId"      type="hidden"  value="{{env('PAY_U_MARKET')}}"   >
              <input name="accountId"       type="hidden"  value="{{env('PAY_U_ACC_ID')}}" >
              <input name="description"     type="hidden"  value="{{$description}}">
              <input name="referenceCode"   type="hidden"  value="TestPayU" >
              <input name="amount"          type="hidden"  value="20000"   >
              <input name="tax"             type="hidden"  value="3193"  >
              <input name="taxReturnBase"   type="hidden"  value="16806" >
              <input name="currency"        type="hidden"  value="COP" >
              <input name="signature"       type="hidden"  value="7ee7cf808ce6a39b17481c54f2c57acc"  >
              <input name="test"            type="hidden"  value="0" >
              <input name="buyerEmail"      type="hidden"  value="test@test.com" >
              <input name="responseUrl"     type="hidden"  value="http://www.test.com/response" >
              <input name="confirmationUrl" type="hidden"  value="http://www.test.com/confirmation" >
              <input name="submit" type="submit" class="btn btn-success ml-auto" value="Realizar donación">          
        </form>
    </div>        
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js'></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  </body>
  <footer>
    <div class="container">
      @include('footer')
    </div>
  </footer>
  <!-- Bootstrap 5 JS -->
 
  <script>
    document.addEventListener('click',function(e){
      // Hamburger menu
      if(e.target.classList.contains('hamburger-toggle')){
        e.target.children[0].classList.toggle('active');
      }
    })         
  </script>
</html>