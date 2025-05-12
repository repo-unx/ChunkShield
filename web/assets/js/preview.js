
document.addEventListener('DOMContentLoaded', function() {
    // Obfuscation preview
    const previewObfuscation = async () => {
        const form = document.querySelector('form');
        if (!form) return;
        
        const formData = new FormData(form);
        formData.append('action', 'preview_obfuscate');
        
        try {
            const response = await fetch('index.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            if (result.success && document.getElementById('obfuscatedCode')) {
                document.getElementById('obfuscatedCode').textContent = result.preview;
            }
        } catch (error) {
            console.error('Preview failed:', error);
        }
    };

    // Chunk preview
    const updateChunkPreview = async () => {
        const form = document.querySelector('form');
        if (!form) return;
        
        const formData = new FormData(form);
        formData.append('action', 'preview_chunk');
        
        try {
            const response = await fetch('index.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            if (result.success && document.getElementById('chunkPreview')) {
                document.getElementById('chunkPreview').textContent = result.preview;
            }
        } catch (error) {
            console.error('Chunk preview failed:', error);
        }
    };
    
    // Loader preview
    const updateLoaderPreview = async () => {
        const form = document.querySelector('form');
        if (!form) return;
        
        const formData = new FormData(form);
        formData.append('action', 'preview_loader');
        
        try {
            const response = await fetch('index.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            if (result.success && document.querySelector('.loader-diagram pre')) {
                document.querySelector('.loader-diagram pre').innerHTML = result.preview;
            }
        } catch (error) {
            console.error('Loader preview failed:', error);
        }
    };

    // Add event listeners for all form inputs
    document.querySelectorAll('form input, form select').forEach(input => {
        ['change', 'input', 'keyup'].forEach(event => {
            input.addEventListener(event, () => {
                if (document.getElementById('obfuscatedCode')) {
                    previewObfuscation();
                }
                if (document.getElementById('chunkPreview')) {
                    updateChunkPreview();
                }
                if (document.querySelector('.loader-diagram')) {
                    updateLoaderPreview();
                }
            });
        });
    });

    // Initial previews
    if (document.getElementById('obfuscatedCode')) {
        previewObfuscation();
    }
    if (document.getElementById('chunkPreview')) {
        updateChunkPreview();
    }
    if (document.querySelector('.loader-diagram')) {
        updateLoaderPreview();
    }
});
