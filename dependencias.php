<?php
include 'Layout/header.php';
include 'Layout/menu.php';
?>

<div class="container mx-auto mt-10">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Listado de Dependencias</h1>
            <button id="crearDependencia" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-400">Crear
                Dependencia</button>
        </div>
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b-2">#</th>
                    <th class="py-2 px-4 border-b-2">Código</th>
                    <th class="py-2 px-4 border-b-2">Nombre</th>
                    <th class="py-2 px-4 border-b-2">Teléfono</th>
                    <th class="py-2 px-4 border-b-2">Dirección</th>
                    <th class="py-2 px-4 border-b-2">Empresa</th>
                    <th class="py-2 px-4 border-b-2">Estado</th>
                    <th class="py-2 px-4 border-b-2">Acciones</th>
                </tr>
            </thead>
            <tbody id="dependenciasTableBody">
                <!-- Table content will be dynamically populated here -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal for Create/Edit Form -->
<div id="dependenciaModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modalTitle">Crear Dependencia</h3>
            <form id="dependenciaForm" class="mt-2 text-left">
                <input type="hidden" id="dependencia_id" name="id">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="cod_dependencia">Código
                        Dependencia</label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="cod_dependencia" name="cod_dependencia" type="text" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="nombre_dependencia">Nombre
                        Dependencia</label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="nombre_dependencia" name="nombre_dependencia" type="text" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="telefono">Teléfono</label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="telefono" name="telefono" type="text">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="direccion">Dirección</label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="direccion" name="direccion" type="text">
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
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="estado">
                        <input type="checkbox" id="estado" name="estado" class="mr-2 leading-tight">
                        <span class="text-sm">Activo</span>
                    </label>
                </div>
                <div class="flex items-center justify-between">
                    <button id="submitDependencia"
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
        loadDependencias();
        loadEmpresas();

        $('#crearDependencia').click(function () {
            openModal('create');
        });

        $('#closeModal').click(function () {
            $('#dependenciaModal').addClass('hidden');
        });

        $('#dependenciaForm').submit(function (e) {
            e.preventDefault();
            let formData = $(this).serialize();
            let url = 'Controllers/dependencias.php';
            let method = $('#dependencia_id').val() ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: formData,
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        Swal.fire('Éxito', response.message, 'success');
                        $('#dependenciaModal').addClass('hidden');
                        loadDependencias();
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error', 'Hubo un problema al procesar la solicitud', 'error');
                }
            });
        });

        function loadDependencias() {
            $.ajax({
                url: 'Controllers/dependencias.php',
                method: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        let tableBody = '';
                        response.data.forEach(function (dependencia) {
                            tableBody += `
                            <tr>
                                <td class="py-2 px-4 border-b">${dependencia.id}</td>
                                <td class="py-2 px-4 border-b">${dependencia.cod_dependencia}</td>
                                <td class="py-2 px-4 border-b">${dependencia.nombre_dependencia}</td>
                                <td class="py-2 px-4 border-b">${dependencia.telefono}</td>
                                <td class="py-2 px-4 border-b">${dependencia.direccion}</td>
                                <td class="py-2 px-4 border-b">${dependencia.nombre_empresa}</td>
                                <td class="py-2 px-4 border-b">${dependencia.estado == 1 ? 'Activo' : 'Inactivo'}</td>
                                <td class="py-2 px-4 border-b">
                                    <button class="editDependencia bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-400" data-id="${dependencia.id}">Editar</button>
                                    <button class="deleteDependencia bg-red-500 text-white px-2 py-1 rounded hover:bg-red-400" data-id="${dependencia.id}">Eliminar</button>
                                </td>
                            </tr>
                        `;
                        });
                        $('#dependenciasTableBody').html(tableBody);
                        bindEditDeleteEvents();
                    } else {
                        Swal.fire('Error', 'No se pudieron cargar las dependencias', 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error', 'Hubo un problema al cargar las dependencias', 'error');
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

        function bindEditDeleteEvents() {
            $('.editDependencia').click(function () {
                let id = $(this).data('id');
                openModal('edit', id);
            });

            $('.deleteDependencia').click(function () {
                let id = $(this).data('id');
                deleteDependencia(id);
            });
        }

        function openModal(action, id = null) {
            $('#modalTitle').text(action === 'create' ? 'Crear Dependencia' : 'Editar Dependencia');
            $('#dependenciaForm')[0].reset();
            $('#dependencia_id').val(id);

            if (action === 'edit') {
                $.ajax({
                    url: 'Controllers/dependencias.php',
                    method: 'GET',
                    data: { id: id },
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            let dependencia = response.data;
                            $('#dependencia_id').val(dependencia.id);
                            $('#cod_dependencia').val(dependencia.cod_dependencia);
                            $('#nombre_dependencia').val(dependencia.nombre_dependencia);
                            $('#telefono').val(dependencia.telefono);
                            $('#direccion').val(dependencia.direccion);
                            $('#empresas_id').val(dependencia.empresas_id);
                            $('#estado').prop('checked', dependencia.estado == 1);
                        } else {
                            Swal.fire('Error', 'No se pudo cargar la información de la dependencia', 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error', 'Hubo un problema al cargar la información de la dependencia', 'error');
                    }
                });
            }

            $('#dependenciaModal').removeClass('hidden');
        }

        function deleteDependencia(id) {
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
                        url: 'Controllers/dependencias.php',
                        method: 'DELETE',
                        data: JSON.stringify({ id: id }),
                        contentType: 'application/json',
                        dataType: 'json',
                        success: function (response) {
                            if (response.status === 'success') {
                                Swal.fire('Eliminado', response.message, 'success');
                                loadDependencias();
                            } else {
                                Swal.fire('Error', response.message, 'error');
                            }
                        },
                        error: function () {
                            Swal.fire('Error', 'Hubo un problema al eliminar la dependencia', 'error');
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