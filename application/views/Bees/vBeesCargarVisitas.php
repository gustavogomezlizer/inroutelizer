<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Excel</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f4f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .upload-container {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            padding: 48px 40px;
            width: 100%;
            max-width: 480px;
            text-align: center;
        }
        .upload-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 20px;
            background: #e8f0fe;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .upload-icon svg { width: 32px; height: 32px; fill: #1a73e8; }
        .upload-title {
            font-size: 22px;
            font-weight: 600;
            color: #202124;
            margin-bottom: 8px;
        }
        .upload-subtitle {
            font-size: 14px;
            color: #5f6368;
            margin-bottom: 28px;
        }
        .drop-zone {
            border: 2px dashed #dadce0;
            border-radius: 12px;
            padding: 40px 20px;
            cursor: pointer;
            transition: all 0.25s ease;
            background: #fafafa;
            position: relative;
        }
        .drop-zone:hover {
            border-color: #1a73e8;
            background: #f0f6fe;
        }
        .drop-zone.dragover {
            border-color: #1a73e8;
            background: #e8f0fe;
            transform: scale(1.01);
        }
        .drop-zone.has-file {
            border-color: #34a853;
            background: #e6f4ea;
            border-style: solid;
        }
        .drop-zone-text {
            font-size: 15px;
            color: #5f6368;
        }
        .drop-zone-text strong {
            color: #1a73e8;
        }
        .drop-zone-hint {
            font-size: 12px;
            color: #9aa0a6;
            margin-top: 10px;
        }
        .file-info {
            display: none;
            margin-top: 16px;
            padding: 12px 16px;
            background: #fff;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            text-align: left;
            font-size: 14px;
            color: #202124;
        }
        .file-info.show { display: flex; align-items: center; gap: 12px; }
        .file-info .file-icon {
            width: 36px; height: 36px;
            background: #34a853;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .file-info .file-icon svg { width: 20px; height: 20px; fill: #fff; }
        .file-info .file-details { flex: 1; }
        .file-info .file-name {
            font-weight: 500;
            word-break: break-all;
        }
        .file-info .file-size {
            font-size: 12px;
            color: #5f6368;
        }
        .file-info .file-remove {
            cursor: pointer;
            color: #5f6368;
            padding: 4px;
            border-radius: 50%;
            transition: background 0.2s;
        }
        .file-info .file-remove:hover { background: #f1f3f4; }
        .upload-btn {
            display: none;
            width: 100%;
            margin-top: 20px;
            padding: 14px;
            background: #1a73e8;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }
        .upload-btn:hover { background: #1557b0; }
        .upload-btn:disabled {
            background: #c4c7c5;
            cursor: not-allowed;
        }
        .upload-btn.show { display: block; }
        .status {
            display: none;
            margin-top: 16px;
            padding: 12px;
            border-radius: 8px;
            font-size: 14px;
        }
        .status.show { display: block; }
        .status.success { background: #e6f4ea; color: #137333; }
        .status.error { background: #fce8e6; color: #c5221f; }
        .status.loading {
            background: #e8f0fe; color: #1a73e8;
            display: flex; align-items: center; justify-content: center; gap: 10px;
        }
        .spinner {
            width: 20px; height: 20px;
            border: 2px solid #c4c7c5;
            border-top-color: #1a73e8;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        #fileInput { display: none; }
    </style>
</head>
<body>

<div class="upload-container">
    <div class="upload-icon">
        <svg viewBox="0 0 24 24"><path d="M9 16h6v-6h4l-7-7-7 7h4zm-4 2h14v2H5z"/></svg>
    </div>
    <div class="upload-title">Sube tu archivo Excel</div>
    <div class="upload-subtitle">Arrastra o selecciona un archivo .xlsx o .xls</div>

    <div class="drop-zone" id="dropZone">
        <div class="drop-zone-text">
            <strong>Haz clic aquí</strong> o arrastra el archivo
        </div>
        <div class="drop-zone-hint">Solo archivos .xlsx y .xls</div>
    </div>

    <div class="file-info" id="fileInfo">
        <div class="file-icon">
            <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM6 20V4h7v5h5v11H6z"/></svg>
        </div>
        <div class="file-details">
            <div class="file-name" id="fileName"></div>
            <div class="file-size" id="fileSize"></div>
        </div>
        <div class="file-remove" id="fileRemove">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
        </div>
    </div>

    <button class="upload-btn" id="uploadBtn">Subir Visitas</button>

    <div class="status" id="status"></div>
</div>

<input type="file" id="fileInput" accept=".xlsx,.xls">

<script>
(function() {
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const fileRemove = document.getElementById('fileRemove');
    const uploadBtn = document.getElementById('uploadBtn');
    const status = document.getElementById('status');

    let selectedFile = null;
    let jsonData = null;

    // --- Drag & Drop ---
    dropZone.addEventListener('click', () => fileInput.click());

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('dragover');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('dragover');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('dragover');
        const files = e.dataTransfer.files;
        if (files.length > 0) handleFile(files[0]);
    });

    fileInput.addEventListener('change', () => {
        if (fileInput.files.length > 0) handleFile(fileInput.files[0]);
    });

    fileRemove.addEventListener('click', () => resetUpload());

    // --- Manejo de archivo ---
    function handleFile(file) {
        const validTypes = [
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-excel'
        ];
        const ext = file.name.split('.').pop().toLowerCase();

        if (!validTypes.includes(file.type) && !['xlsx', 'xls'].includes(ext)) {
            showStatus('Solo se permiten archivos .xlsx o .xls', 'error');
            return;
        }

        selectedFile = file;
        fileName.textContent = file.name;
        fileSize.textContent = formatSize(file.size);

        fileInfo.classList.add('show');
        dropZone.classList.add('has-file');
        uploadBtn.classList.add('show');
        status.classList.remove('show');
        status.className = 'status';

        // Leer y convertir a JSON
        convertToJSON(file);
    }

    function convertToJSON(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            try {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, { type: 'array' });
                const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                jsonData = XLSX.utils.sheet_to_json(firstSheet, { defval: '' });
                showStatus('✅ Archivo convertido a JSON correctamente. ' + jsonData.length + ' filas.', 'success');
                setTimeout(() => status.classList.remove('show'), 4000);
            } catch (err) {
                jsonData = null;
                showStatus('Error al leer el archivo: ' + err.message, 'error');
            }
        };
        reader.onerror = function() {
            jsonData = null;
            showStatus('Error al leer el archivo.', 'error');
        };
        reader.readAsArrayBuffer(file);
    }

    // --- Enviar JSON al controlador ---
    uploadBtn.addEventListener('click', function() {
        if (!jsonData) {
            showStatus('No hay datos para enviar. Revisa el archivo.', 'error');
            return;
        }

        uploadBtn.disabled = true;
        showStatus('', 'loading');

        const csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
        const csrfHash = '<?= $this->security->get_csrf_hash(); ?>';

        const payload = {};
        payload[csrfName] = csrfHash;
        payload['data'] = jsonData;
        payload['filename'] = selectedFile.name;

        fetch('<?php echo base_url("index.php/TokenBees/LeerVisitasJson") ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(resp => {
            if (resp.success) {
                showStatus('✅ ' + resp.message, 'success');
            } else {
                showStatus('❌ ' + resp.message, 'error');
            }
        })
        .catch(err => {
            showStatus('❌ Error de conexión: ' + err.message, 'error');
        })
        .finally(() => {
            uploadBtn.disabled = false;
        });
    });

    // --- Utilidades ---
    function showStatus(msg, type) {
        status.className = 'status show ' + type;
        if (type === 'loading') {
            status.innerHTML = '<div class="spinner"></div> Procesando...';
        } else {
            status.textContent = msg;
        }
    }

    function resetUpload() {
        selectedFile = null;
        jsonData = null;
        fileInput.value = '';
        fileInfo.classList.remove('show');
        dropZone.classList.remove('has-file');
        uploadBtn.classList.remove('show');
        status.classList.remove('show');
        status.className = 'status';
    }

    function formatSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(1) + ' MB';
    }
})();
</script>
</body>
</html>