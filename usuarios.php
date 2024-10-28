<?php
include 'Layout/header.php';
include 'Layout/menu.php';
?>

<div class="container mx-auto mt-10">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Listado de Usuarios</h1>
            <button id="crearUsuario" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-400">Crear
                Usuario</button>
        </div>
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b-2">#</th>
                    <th class="py-2 px-4 border-b-2">Nombres</th>
                    <th class="py-2 px-4 border-b-2">Apellidos</th>
                    <th class="py-2 px-4 border-b-2">Correo</th>
                    <th class="py-2 px-4 border-b-2">Teléfono</th>
                    <th class="py-2 px-4 border-b-2">Empresa</th>
                    <th class="py-2 px-4 border-b-2">Dependencia</th>
                    <th class="py-2 px-4 border-b-2">Estado</th>
                    <th class="py-2 px-4 border-b-2">Acciones</th>
                </tr>
            </thead>
            <tbody id="usuariosTableBody">
                <!-- Table content will be dynamically populated here -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal for Create/Edit Form -->
<div id="usuarioModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modalTitle">Crear Usuario</h3>
            <form id="usuarioForm" class="mt-2 text-left">
                <input type="hidden" id="usuario_id" name="id">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="nombres">Nombres</label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="nombres" name="nombres" type="text" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="apellidos">Apellidos</label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="apellidos" name="apellidos" type="text" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="correo">Correo</label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="correo" name="correo" type="email" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="telefono">Teléfono</label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="telefono" name="telefono" type="text">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="empresas_id">Empresa</label>
                    <select
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="empresas_id" name="empresas_id" required>
                        <!-- Options will be populated dynamically -->
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="dependencias_id">Dependencia</label>
                    <select
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="dependencias_id" name="dependencias_id" required>
                        <!-- Options will be populated dynamically -->
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="estado">
                        <input type="checkbox" id="estado" name="estado" class="mr-2 leading-tight">
                        <span class="text-sm">Activo</span>
                    </label>
                </div>
                <div class="flex items-center justify-between">
                    <button id="submitUsuario"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        type="submit">Guardar</button>
                    <button id="closeModal"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        type="button">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        loadUsuarios();
        loadEmpresas();

        $('#crearUsuario').click(function () {
            openModal('create');
        });

        $('#closeModal').click(function () {
            $('#usuarioModal').addClass('hidden');
        });

        $('#usuarioForm').submit(function (e) {
            e.preventDefault();
            let formData = $(this).serialize();
            let url = 'Controllers/usuarios.php';
            let method = $('#usuario_id').val() ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: formData,
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        Swal.fire('Éxito', response.message, 'success');
                        $('#usuarioModal').addClass('hidden');
                        loadUsuarios();
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error', 'Hubo un problema al procesar la solicitud', 'error');
                }
            });
        });

        function loadUsuarios() {
            $.ajax({
                url: 'Controllers/usuarios.php',
                method: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        let tableBody = '';
                        response.data.forEach(function (usuario) {
                            tableBody += `
                            <tr>
                                <td class="py-2 px-4 border-b">${usuario.id}</td>
                                <td class="py-2 px-4 border-b">${usuario.nombres}</td>
                                <td class="py-2 px-4 border-b">${usuario.apellidos}</td>
                                <td class="py-2 px-4 border-b">${usuario.correo}</td>
                                <td class="py-2 px-4 border-b">${usuario.telefono}</td>
                                <td class="py-2 px-4 border-b">${usuario.nombre_empresa}</td>
                                <td class="py-2 px-4 border-b">${usuario.nombre_dependencia}</td>
                                <td class="py-2 px-4 border-b">${usuario.estado == 1 ? 'Activo' : 'Inactivo'}</td>
                                <td class="py-2 px-4 border-b">
                                    <button class="editUsuario bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-400" data-id="${usuario.id}">Editar</button>
                                    <button class="deleteUsuario bg-red-500 text-white px-2 py-1 rounded hover:bg-red-400" data-id="${usuario.id}">Eliminar</button>
                                </td>
                            </tr>
                        `;
                        });
                        $('#usuariosTableBody').html(tableBody);
                        bindEditDeleteEvents();
                    } else {
                        Swal.fire('Error', 'No se pudieron cargar los usuarios', 'error');
                    }
                },
                error: function () {

                    Swal.fire('Error', 'Hubo un problema al cargar los usuarios', 'error');
                }
            });
        }

        function loadEmpresas() {
            $.ajax({
                url: 'Controllers/empresas.php',
                method: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        let options = '<option value="">Seleccione una empresa</option>';
                        response.data.forEach(function (empresa) {
                            options += `<option value="${empresa.id}">${empresa.nombre_empresa}</option>`;
                        });
                        $('#empresas_id').html(options);
                    } else {
                        Swal.fire('Error', 'No se pudieron cargar las empresas', 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error', 'Hubo un problema al cargar las empresas', 'error');
                }
            });
        }

        $('#empresas_id').change(function () {
            loadDependencias($(this).val());
        });

        function loadDependencias(empresaId) {
            $.ajax({
                url: 'Controllers/dependencias.php',
                method: 'GET',
                data: { empresas_id: empresaId },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        let options = '<option value="">Seleccione una dependencia</option>';
                        response.data.forEach(function (dependencia) {
                            options += `<option value="${dependencia.id}">${dependencia.nombre_dependencia}</option>`;
                        });
                        $('#dependencias_id').html(options);
                    } else {
                        Swal.fire('Error', 'No se pudieron cargar las dependencias', 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error', 'Hubo un problema al cargar las dependencias', 'error');
                }
            });
        }

        function bindEditDeleteEvents() {
            $('.editUsuario').click(function () {
                let id = $(this).data('id');
                openModal('edit', id);
            });

            $('.deleteUsuario').click(function () {
                let id = $(this).data('id');
                deleteUsuario(id);
            });
        }

        function openModal(action, id = null) {
            $('#modalTitle').text(action === 'create' ? 'Crear Usuario' : 'Editar Usuario');
            $('#usuarioForm')[0].reset();
            $('#usuario_id').val(id);

            if (action === 'edit') {
                $.ajax({
                    url: 'Controllers/usuarios.php',
                    method: 'GET',
                    data: { id: id },
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            let usuario = response.data;
                            $('#usuario_id').val(usuario.id);
                            $('#nombres').val(usuario.nombres);
                            $('#apellidos').val(usuario.apellidos);
                            $('#correo').val(usuario.correo);
                            $('#telefono').val(usuario.telefono);
                            $('#empresas_id').val(usuario.empresas_id);
                            loadDependencias(usuario.empresas_id);
                            setTimeout(() => {
                                $('#dependencias_id').val(usuario.dependencias_id);
                            }, 500);
                            $('#estado').prop('checked', usuario.estado == 1);
                        } else {
                            Swal.fire('Error', 'No se pudo cargar la información del usuario', 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error', 'Hubo un problema al cargar la información del usuario', 'error');
                    }
                });
            }

            $('#usuarioModal').removeClass('hidden');
        }

        function deleteUsuario(id) {
            Swal.fire({
                title: '¿Está seguro?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'Controllers/usuarios.php',
                        method: 'DELETE',
                        data: JSON.stringify({ id: id }),
                        contentType: 'application/json',
                        dataType: 'json',
                        success: function (response) {
                            if (response.status === 'success') {
                                Swal.fire('Eliminado', response.message, 'success');
                                loadUsuarios();
                            } else {
                                Swal.fire('Error', response.message, 'error');
                            }
                        },
                        error: function () {
                            Swal.fire('Error', 'Hubo un problema al eliminar el usuario', 'error');
                        }
                    });
                }
            });
        }
    });
</script>

<?php
include 'Layout/footer.php';
?>