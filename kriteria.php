<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kriteria & Bobot - BranchWise</title>
    
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Materialize CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #1565c0;
            --primary-dark: #0d47a1;
            --secondary-color: #26a69a;
            --light-gray: #f5f5f5;
            --dark-gray: #333;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }
        
        main {
            flex: 1 0 auto;
            margin-left: 250px;
        }
        
        /* Sidebar Styles */
        .sidenav {
            width: 250px;
            padding-top: 20px;
            position: fixed;
            height: 100vh;
            z-index: 999;
        }
        
        .sidenav .user-view {
            padding: 32px 32px 16px;
        }
        
        .sidenav .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .sidenav li>a {
            padding: 0 20px;
            height: 48px;
            line-height: 48px;
            margin: 4px 0;
        }
        
        .sidenav li>a>i {
            margin-right: 12px;
            color: rgba(0,0,0,0.6);
        }
        
        .sidenav li>a.active {
            background-color: rgba(21,101,192,0.1);
            color: var(--primary-color);
            font-weight: 500;
        }
        
        .sidenav li>a.active>i {
            color: var(--primary-color);
        }
        
        /* Header Styles */
        .header {
            padding: 0 24px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            background-color: white;
            height: 64px;
            display: flex;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 998;
        }
        
        .page-title {
            font-size: 1.5rem;
            font-weight: 500;
            margin: 0;
            padding-left: 12px;
        }
        
        /* Content Styles */
        .content-wrapper {
            padding: 24px;
        }
        
        .card {
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 24px;
        }
        
        .card-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
            color: var(--dark-gray);
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        table th {
            background-color: #f5f5f5;
            font-weight: 500;
            padding: 12px 15px;
            text-align: left;
        }
        
        table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .badge-benefit {
            background-color: #2ecc71;
            color: white;
        }
        
        .badge-cost {
            background-color: #e74c3c;
            color: white;
        }
        
        .action-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: #7f8c8d;
            font-size: 16px;
            transition: color 0.2s;
            padding: 5px;
            border-radius: 50%;
            margin: 0 3px;
        }
        
        .action-btn:hover {
            background-color: rgba(0,0,0,0.05);
        }
        
        .edit-btn:hover {
            color: var(--primary-color);
        }
        
        .delete-btn:hover {
            color: #e74c3c;
        }
        
        .total-weight {
            padding: 15px 20px;
            background-color: #f5f5f5;
            border-top: 1px solid #eee;
            font-weight: 500;
            text-align: right;
        }
        
        /* Add Criteria Button */
        .add-criteria-btn {
            margin-bottom: 20px;
        }
        
        /* Responsive Adjustments */
        @media only screen and (max-width: 992px) {
            main {
                margin-left: 0;
            }
            
            .sidenav {
                transform: translateX(-105%);
            }
        }
    </style>
</head>
<body>
    <!-- Side Navigation -->
    <ul id="slide-out" class="sidenav sidenav-fixed">
        <li>
            <div class="user-view">
                <div class="logo">
                    <span class="blue-text text-darken-2" style="font-size: 1.5rem; font-weight: 600;">BranchWise</span>
                </div>
            </div>
        </li>
        <li><a href="dashboard.php"><i class="material-icons">dashboard</i>Dashboard</a></li>
        <li><a href="lokasi.php"><i class="material-icons">location_on</i>Lokasi Cabang</a></li>
        <li><a class="active" href="kriteria.php"><i class="material-icons">assessment</i>Kriteria & Bobot</a></li>
        <li><a href="matriks.php"><i class="material-icons">grid_on</i>Matriks</a></li>
        <li><a href="hasil_perhitungan.php"><i class="material-icons">calculate</i>Hasil Perhitungan</a></li>
        <li><div class="divider"></div></li>
        <li><a href="logout.html"><i class="material-icons">exit_to_app</i>Keluar</a></li>
    </ul>

    <!-- Main Content -->
    <main>
        <!-- Header -->
        <div class="header">
            <div class="row valign-wrapper" style="margin-bottom: 0; width: 100%;">
                <div class="col s6">
                    <a href="#" data-target="slide-out" class="sidenav-trigger hide-on-large-only"><i class="material-icons">menu</i></a>
                    <h1 class="page-title">Kriteria & Bobot</h1>
                </div>
                <div class="col s6 right-align">
                    <span class="btn-flat dropdown-trigger" data-target="profile-dropdown">
                        <i class="material-icons left">account_circle</i>
                        Username
                        <i class="material-icons right">arrow_drop_down</i>
                    </span>
                    
                    <!-- Profile Dropdown -->
                    <ul id="profile-dropdown" class="dropdown-content">
                        <li><a href="#!"><i class="material-icons">person</i>Profil</a></li>
                        <li><a href="#!"><i class="material-icons">settings</i>Pengaturan</a></li>
                        <li class="divider"></li>
                        <li><a href="#!"><i class="material-icons">exit_to_app</i>Keluar</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="content-wrapper">
            <div class="card">
            <div class="card-header">
                <div class="row" style="margin-bottom: 0; width: 100%;"> <div class="col s12 m6"> <h2 class="card-title">Daftar Kriteria</h2>
                    </div>
                    <div class="col s12 m6 right-align"> <a href="#add-criteria-modal" class="btn waves-effect waves-light blue darken-2 modal-trigger">
                            <i class="material-icons left">add</i>Tambah Kriteria
                        </a>
                    </div>
                </div> </div>
                
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama Kriteria</th>
                                <th>Bobot</th>
                                <th>Jenis</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>C1</td>
                                <td>Harga</td>
                                <td>30%</td>
                                <td><span class="badge badge-cost">Cost</span></td>
                                <td>
                                    <button class="action-btn edit-btn" title="Edit">
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button class="action-btn delete-btn" title="Hapus">
                                        <i class="material-icons">delete</i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>C2</td>
                                <td>Kualitas</td>
                                <td>25%</td>
                                <td><span class="badge badge-benefit">Benefit</span></td>
                                <td>
                                    <button class="action-btn edit-btn" title="Edit">
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button class="action-btn delete-btn" title="Hapus">
                                        <i class="material-icons">delete</i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>C3</td>
                                <td>Pelayanan</td>
                                <td>20%</td>
                                <td><span class="badge badge-benefit">Benefit</span></td>
                                <td>
                                    <button class="action-btn edit-btn" title="Edit">
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button class="action-btn delete-btn" title="Hapus">
                                        <i class="material-icons">delete</i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>C4</td>
                                <td>Lokasi</td>
                                <td>15%</td>
                                <td><span class="badge badge-benefit">Benefit</span></td>
                                <td>
                                    <button class="action-btn edit-btn" title="Edit">
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button class="action-btn delete-btn" title="Hapus">
                                        <i class="material-icons">delete</i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>C5</td>
                                <td>Waktu Pengiriman</td>
                                <td>10%</td>
                                <td><span class="badge badge-cost">Cost</span></td>
                                <td>
                                    <button class="action-btn edit-btn" title="Edit">
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button class="action-btn delete-btn" title="Hapus">
                                        <i class="material-icons">delete</i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="total-weight">
                    Total Bobot: 100%
                </div>
            </div>
        </div>
    </main>

    <!-- Add Criteria Modal -->
    <div id="add-criteria-modal" class="modal">
        <div class="modal-content">
            <h4>Tambah Kriteria Baru</h4>
            <form>
                <div class="row">
                    <div class="input-field col s12 m6">
                        <input id="criteria_code" type="text" class="validate" required>
                        <label for="criteria_code">Kode Kriteria</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <input id="criteria_name" type="text" class="validate" required>
                        <label for="criteria_name">Nama Kriteria</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12 m6">
                        <input id="criteria_weight" type="number" min="1" max="100" class="validate" required>
                        <label for="criteria_weight">Bobot (%)</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <select id="criteria_type" required>
                            <option value="" disabled selected>Pilih Jenis Kriteria</option>
                            <option value="benefit">Benefit</option>
                            <option value="cost">Cost</option>
                        </select>
                        <label>Jenis Kriteria</label>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-red btn-flat">Batal</a>
            <a href="#!" class="modal-close waves-effect waves-green btn blue">Simpan Kriteria</a>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        $(document).ready(function(){
            // Initialize sidenav
            $('.sidenav').sidenav();
            
            // Initialize dropdown
            $('.dropdown-trigger').dropdown();
            
            // Initialize modal
            $('.modal').modal();
            
            // Initialize select
            $('select').formSelect();
            
            // Delete button functionality
            $('.delete-btn').click(function() {
                const criteriaRow = $(this).closest('tr');
                const criteriaName = criteriaRow.find('td:nth-child(3)').text();
                
                if (confirm(`Apakah Anda yakin ingin menghapus kriteria "${criteriaName}"?`)) {
                    criteriaRow.remove();
                    M.toast({html: `Kriteria "${criteriaName}" telah dihapus`});
                    updateTotalWeight();
                }
            });
            
            // Edit button functionality
            $('.edit-btn').click(function() {
                const criteriaRow = $(this).closest('tr');
                const criteriaName = criteriaRow.find('td:nth-child(3)').text();
                $('#add-criteria-modal h4').text(`Edit Kriteria: ${criteriaName}`);
                
                // Fill form with existing data
                $('#criteria_code').val(criteriaRow.find('td:nth-child(2)').text());
                $('#criteria_name').val(criteriaName);
                $('#criteria_weight').val(criteriaRow.find('td:nth-child(4)').text().replace('%', ''));
                $('#criteria_type').val(criteriaRow.find('td:nth-child(5) span').text().toLowerCase());
                $('select').formSelect();
                
                $('#add-criteria-modal').modal('open');
            });
            
            // Function to update total weight
            function updateTotalWeight() {
                let total = 0;
                $('tbody tr').each(function() {
                    const weight = parseInt($(this).find('td:nth-child(4)').text().replace('%', ''));
                    total += weight;
                });
                $('.total-weight').text(`Total Bobot: ${total}%`);
            }
        });
    </script>
</body>
</html>