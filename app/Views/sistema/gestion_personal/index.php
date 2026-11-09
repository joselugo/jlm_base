 <!--<ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="#index/home">Inicio</a></li>
        <li class="breadcrumb-item">Sistema</li>
        <li class="breadcrumb-item active">Gestión de personal</li>
</ol> 
<h1 class="page-header">Operadores</h1>-->
 <div class="panel panel-default">
   <div class="panel-heading">
     <h4 class="panel-title"><b>GESTIÓN PERSONAL</b></h4>
     <div class="panel-heading-btn">
       <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand"><i class="fa fa-expand"></i></a>
       <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload" onclick="updateoperadores()"><i class="fas fa-sync-alt"></i></a>
     </div>

   </div>
   <div class="panel-body border-panel">

     <table id="data-operadores" class="display nowrap table table-striped table-bordered dataTable no-footer dtr-inline collapsed" cellspacing="0" width="100%" role="grid" aria-describedby="data-operadores_info" style="width: 100%;">
       <thead>
         <tr role="row">
           <th class="all sorting_asc" tabindex="0" aria-controls="data-operadores" rowspan="1" colspan="1" data-column-index="0" style="width: 20px;" aria-label="ID: Activar para ordenar la columna de manera descendente" aria-sort="ascending">ID</th>
           <th class="sorting" tabindex="0" aria-controls="data-operadores" rowspan="1" colspan="1" data-column-index="1" style="width: 0px; display: none;" aria-label="Nombre: Activar para ordenar la columna de manera ascendente">Nombre</th>
           <th class="all sorting" tabindex="0" aria-controls="data-operadores" rowspan="1" colspan="1" data-column-index="2" aria-label="Usuario: Activar para ordenar la columna de manera ascendente">Usuario</th>
           <th class="all sorting" tabindex="0" aria-controls="data-operadores" rowspan="1" colspan="1" data-column-index="2" aria-label="Usuario: Activar para ordenar la columna de manera ascendente">Sucursal</th>
           <th class="sorting" tabindex="0" aria-controls="data-operadores" rowspan="1" colspan="1" data-column-index="3" style="width: 0px; display: none;" aria-label="Correo: Activar para ordenar la columna de manera ascendente">Correo</th>
           <th class="sorting" tabindex="0" aria-controls="data-operadores" rowspan="1" colspan="1" data-column-index="4" style="width: 0px; display: none;" aria-label="Rol: Activar para ordenar la columna de manera ascendente">Rol</th>
           <th class="sorting" tabindex="0" aria-controls="data-operadores" rowspan="1" colspan="1" data-column-index="5" style="width: 0px; display: none;" aria-label="Estado: Activar para ordenar la columna de manera ascendente">Estado</th>
           <th class="all text-center sorting_disabled" data-orderable="false" rowspan="1" colspan="1" data-column-index="6" style="width: 36px;" aria-label=""></th>
         </tr>
       </thead>

     </table>



   </div>
 </div>

 <script>
   var base_url = "<?= base_url(); ?>"

   function delete_operador(id, t, n) {
     $.confirm({
       theme: 'supervan',
       draggable: false,
       closeIcon: true,
       animationBounce: 2.5,
       escapeKey: false,
       content: 'Esta seguro que desea eliminar este operador? ',
       title: '<i class="fa fa-question-circle fa-lg icodialog"></i> Eliminar Operador',
       buttons: {
         Eliminar: {
           text: '<i class="far fa-trash-alt icodialog"></i> Eliminar', // With spaces and symbols
           action: function() {
             $.post(base_url + "sistema/delete_operador", {
                 id: id,
                 token: t,
                 nombre: n
               })
               .done(function(data) {
                 if (data == 'error') {
                   alerta('error', data);
                 } else {
                   alerta('exito', "Usuario eliminado");
                   updateoperadores();
                 }
               });

           }
         }
       }
     });
   }

   $(function() {
     App.setPageTitle('Gestión personal');

     configtable('#data-operadores', 'Operadores');

     var operadores = $('#data-operadores').DataTable({
       "ajax": base_url + "sistema/get_personal_json",
       "deferRender": true,
       "idDataTables": "1",
       "aoColumns": [{
           mData: 'id',
           sWidth: '20px'
         },
         {
           mData: 'nombre'
         },
         {
           mData: 'username'
         },
         {
           mData: 'sucursal'
         },
         {
           mData: 'correo'
         },
         {
           mData: 'rol'
         },
         {
           mData: 'estado'
         },
         {
           mData: 'tool',
           sClass: 'text-center',
           sWidth: '30px'
         }
       ],
       initComplete: function(oSettings, json) {
         $("#data-operadores_wrapper .dt-buttons")
           .append('<div class="btn-group">\
      <a class="btn btn-success" href="#sistema/new_operador"><i class="fas fa-plus"></i> Nuevo</a>\
    </div>');
       }
     }).on('draw', function() {
       $('img[data-name]').initial({
         height: 64,
         width: 64,
         charCount: 2,
         fontSize: 24,
         fontWeight: 600
       });

     });

     updateoperadores = function() {
       operadores.ajax.reload(null, false);
       $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
     }

   })
 </script>