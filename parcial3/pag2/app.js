$(document).ready(function() {
  // Global Settings
  let edit = false;

  // Testing Jquery
  console.log('jquery is working!');
  fetchProductos();
  $('#productos-result').hide();


  // search key type event
  $('#search').keyup(function() {
    if($('#search').val()) {
      let search = $('#search').val();
      $.ajax({
        url: 'task-search.php',
        data: {search},
        type: 'POST',
        success: function (response) {
          if(!response.error) {
            let producto = JSON.parse(response);
            let template = '';
            producto.forEach(productos => {
              template += `
                      <li><a href="#" class="productos-item">${productos.nombre}</a></li>
                    ` 
            });
            $('#productos-result').show();
            $('#container').html(template);
          }
        } 
      })
    } else {
      $('#productos-result').hide();
    }
  });

  $('#productos-form').submit(e => {
    e.preventDefault();
    const postData = {
      nombre: $('#nombre').val(),
      precio: $('#precio').val(),
      existencia: $('#existencia').val(),
      imagen: $('#imagen').val(),
      medida: $('#medida').val(),
      id: $('#productosId').val()
    };
    const url = edit === false ? 'task-add.php' : 'task-edit.php';
    console.log(postData, url);
    $.post(url, postData, (response) => {
      console.log(response);
      $('#productos-form').trigger('reset');
      fetchProductos();
    });
  });

  // Fetching Tasks
  function fetchProductos() {
    $.ajax({
      url: 'tasks-list.php',
      type: 'GET',
      success: function(response) {
        const tasks = JSON.parse(response);
        let template = '';
        tasks.forEach(productos => {
          template += `
                  <tr productosId="${productos.id}">
                  <td>${productos.id}</td>
                  <td>
                  <a href="#" class="productos-item">
                    ${productos.nombre} 
                  </a>
                  </td>
                  <td>${productos.precio}</td>
                  <td>${productos.existencia}</td>
                  <td>${productos.medida}</td>
                  <td>
                    <button class="task-delete btn btn-danger">
                      Delete 
                    </button>
                  </td>
                  </tr>
                `
        });
        $('#productos').html(template);
      }
    });
  }

  // Get a Single Task by Id 
  $(document).on('click', '.productos-item', (e) => {
    const element = $(this)[0].activeElement.parentElement.parentElement;
    const id = $(element).attr('productosId');
    $.post('task-single.php', {id}, (response) => {
      const productos = JSON.parse(response);
      $('#nombre').val(productos.nombre);
      $('#precio').val(productos.precio);
      $('#existencia').val(productos.existencia);
      $('#imagen').val(productos.imagen);
      $('#medida').val(productos.medida);
      $('#productosId').val(productos.id);
      edit = true;
    });
    e.preventDefault();
  });

  // Delete a Single Task
  $(document).on('click', '.task-delete', (e) => {
    if(confirm('Are you sure you want to delete it?')) {
      const element = $(this)[0].activeElement.parentElement.parentElement;
      const id = $(element).attr('productosId');
      $.post('task-delete.php', {id}, (response) => {
        fetchProductos();
      });
    }
  });
});
