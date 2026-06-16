  <div class="row">
      <div class="col-12 col-md-6 mb-2">
          <strong>Nombres:</strong>
          {{ $empleado->nombres }}
      </div>
      <div class="col-12 col-md-6 mb-2">
          <strong>Apellidos:</strong>
          {{ $empleado->apellidos }}
      </div>

      <div class="col-12 col-md-6 mb-2">
          <strong>Nacionalidad:</strong>
          {{ $empleado->nacionalidad }}
      </div>
      <div class="col-12 col-md-6 mb-2">
          <strong>Fecha Nacimiento:</strong>
          {{ $empleado->fecnacimiento }}
      </div>


      <div class="col-12 col-md-6 mb-2">
          <strong>Area:</strong>
          {{ $empleado->area->nombre }}
      </div>
      <div class="col-12 col-md-6 mb-2">
          <strong>Cubre Relevos:</strong>
          @if ($empleado->cubrerelevos)
              <span class="badge bg-success">SI</span>
          @else
              <span class="badge bg-secondary">NO</span>
          @endif
      </div>
      <div class="col-12 col-md-6 mb-2">
          <strong>Usuario:</strong>
          @if ($empleado->user_id)
              <span class="badge bg-success">Generado</span>
              @if ($empleado->user->status)
                  <span class="badge badge-pill badge-primary">Activo</span>
              @else
                  <span class="badge badge-pill badge-secondary">Inactivo</span>
              @endif
          @else
              <span class="badge bg-secondary">No generado</span>
          @endif
      </div>
      <div class="col-12 mb-2">
          <strong>Direccion:</strong>
          {{ $empleado->direccion }}
      </div>
  </div>
  <hr>
  <div class="row">
      <div class="col-12 col-md-6">
          <small> <strong>Padece enfermedades:</strong></small> <br>
          {{ $empleado->enfermedades ?? 'N/A' }}
      </div>
      <div class="col-12 col-md-6">
          <small> <strong>Padece alergias:</strong></small> <br>
          {{ $empleado->alergias ?? 'N/A' }}
      </div>
  </div>
  <hr>
  <div class="row">
      <div class="col-12 col-md-6">
          <small><strong>Persona Referencia:</strong></small> <br>
          {{ $empleado->persona_referencia ?? 'N/A' }}
      </div>
      <div class="col-12 col-md-3">
          <small><strong>Parentezco:</strong></small> <br>
          {{ $empleado->parentezco_referencia ?? 'N/A' }}
      </div>
      <div class="col-12 col-md-3">
          <small><strong>Telf. Ref.:</strong></small> <br>
          {{ $empleado->telefono_referencia ?? 'N/A' }}
      </div>
  </div>
  <hr>
  <div class="row">

      <div class="col-12 col-md-4">
          <label>Cedula Anverso</label> <br>
          @if ($empleado->cedulaanverso)
              <img src="{{ asset('storage/' . $empleado->cedulaanverso) }}" class="img-thumbnail img-preview2">
          @else
              <img src="{{ asset('images/anverso.png') }}" class="img-thumbnail img-preview2">
          @endif

      </div>
      <div class="col-12 col-md-4">
          <label>Cedula Reverso</label> <br>
          @if ($empleado->cedulareverso)
              <img src="{{ asset('storage/' . $empleado->cedulareverso) }}" class="img-thumbnail img-preview2">
          @else
              <img src="{{ asset('images/reverso.png') }}" class="img-thumbnail img-preview2">
          @endif

      </div>
  </div>
  <hr>
  @if ($empleado->direccionlat && $empleado->direccionlng)
      <div class="form-group mt-4 ">
          <label for="mapa">Ubicación del Domicilio:</label>
          <div id="mi_mapa" class="border border-dark rounded-lg" style="width: 100%; height: 500px;">
          </div>
      </div>
  @endif
  @section('js')
      <script>
          function initMap() {
              const ubicacion = {
                  lat: {{ $empleado->direccionlat ?? '-17.783604' }},
                  lng: {{ $empleado->direccionlng ?? '-63.180395' }}
              };

              const map = new google.maps.Map(document.getElementById("mi_mapa"), {
                  zoom: 17,
                  center: ubicacion,
                  mapId: "DEMO_MAP_ID" // Necesario para AdvancedMarkerElement
              });

              // Usar el nuevo AdvancedMarkerElement en lugar del Marker deprecado
              const marker = new google.maps.marker.AdvancedMarkerElement({
                  position: ubicacion,
                  map: map,
                  content: createCustomMarker()
              });
          }

          function createCustomMarker() {
              const img = document.createElement('img');
              img.src = "{{ asset('images/punt.png') }}";
              img.style.width = '35px';
              img.style.height = '35px';
              return img;
          }

          // Cargar Google Maps de forma asíncrona
          (function() {
              const script = document.createElement('script');
              script.src =
                  'https://maps.googleapis.com/maps/api/js?key={{ config('googlemaps.api_key') }}&loading=async&libraries=marker&callback=initMap';
              script.async = true;
              script.defer = true;
              document.head.appendChild(script);
          })();
      </script>
  @endsection

  <style>
      .img-preview {
          max-height: 250px;
      }

      .img-preview2 {
          max-height: 150px;
      }
  </style>
