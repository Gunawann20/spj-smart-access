// ============================================
// HELPER FUNCTIONS
// ============================================

function scrollToUpload() {
    closeSidebar();
    document.getElementById('upload').scrollIntoView({ behavior: 'smooth' });
}

function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('active');
}

function closeSidebar() {
    const sidebar = document.querySelector('.sidebar');
    sidebar.classList.remove('active');
}

function filterByType(type) {
    closeSidebar();
    document.getElementById('documents').scrollIntoView({ behavior: 'smooth' });
    setTimeout(() => {
        document.getElementById('filterType').value = type;
        filterDocuments();
    }, 500);
}

function handleUpload(event) {
    event.preventDefault();
    
    const docType = document.getElementById('docType').value;
    const docTitle = document.getElementById('docTitle').value;
    const docDescription = document.getElementById('docDescription').value;
    const docYear = document.getElementById('docYear').value;
    
    if (!docType || !docTitle || !docYear) {
        alert('Mohon isi semua field yang diperlukan (*)');
        return;
    }
    
    // Simulating upload
    showNotification('Dokumen sedang diunggah...', 'info');
    
    setTimeout(() => {
        showNotification(`"${docTitle}" berhasil diunggah!`, 'success');
        event.target.reset();
    }, 1500);
}

// ============================================
// SEARCH & FILTER FUNCTIONALITY
// ============================================

const searchInput = document.getElementById('searchInput');
const filterType = document.getElementById('filterType');
const filterYear = document.getElementById('filterYear');

// Event listeners for filtering
if (searchInput) {
    searchInput.addEventListener('keyup', filterDocuments);
}

if (filterType) {
    filterType.addEventListener('change', filterDocuments);
}

if (filterYear) {
    filterYear.addEventListener('change', filterDocuments);
}

function filterDocuments() {
    const searchTerm = searchInput.value.toLowerCase();
    const typeFilter = filterType.value;
    const yearFilter = filterYear.value;
    
    const docCards = document.querySelectorAll('.doc-card');
    let visibleCount = 0;
    
    docCards.forEach(card => {
        const title = card.querySelector('h3').textContent.toLowerCase();
        const type = card.querySelector('.doc-type-badge').textContent.trim();
        const dateText = card.querySelector('.doc-date').textContent;
        const year = dateText.match(/\d{4}/)[0];
        
        // Check if card matches all filters
        const matchesSearch = title.includes(searchTerm);
        const matchesType = !typeFilter || type === typeFilter;
        const matchesYear = !yearFilter || year === yearFilter;
        
        if (matchesSearch && matchesType && matchesYear) {
            card.style.display = '';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    // Show "no results" message if needed
    showNoResultsMessage(visibleCount === 0);
}

function showNoResultsMessage(show) {
    let noResults = document.querySelector('.no-results');
    
    if (show && !noResults) {
        noResults = document.createElement('div');
        noResults.className = 'no-results';
        noResults.innerHTML = `
            <div style="text-align: center; padding: 3rem; color: #6c757d;">
                <p style="font-size: 1.2rem;">Tidak ada dokumen yang sesuai dengan filter Anda</p>
            </div>
        `;
        document.querySelector('.documents-grid').appendChild(noResults);
    } else if (!show && noResults) {
        noResults.remove();
    }
}

// ============================================
// DRAG & DROP FUNCTIONALITY
// ============================================

const uploadArea = document.querySelector('.upload-area');
const fileInput = document.getElementById('fileInput');

if (uploadArea) {
    uploadArea.addEventListener('dragover', handleDragOver);
    uploadArea.addEventListener('dragleave', handleDragLeave);
    uploadArea.addEventListener('drop', handleDrop);
    uploadArea.addEventListener('click', () => fileInput.click());
}

function handleDragOver(event) {
    event.preventDefault();
    event.stopPropagation();
    uploadArea.style.borderColor = '#0056b3';
    uploadArea.style.backgroundColor = 'rgba(0, 123, 255, 0.15)';
}

function handleDragLeave(event) {
    event.preventDefault();
    event.stopPropagation();
    uploadArea.style.borderColor = '#007bff';
    uploadArea.style.backgroundColor = 'rgba(0, 123, 255, 0.05)';
}

function handleDrop(event) {
    event.preventDefault();
    event.stopPropagation();
    uploadArea.style.borderColor = '#007bff';
    uploadArea.style.backgroundColor = 'rgba(0, 123, 255, 0.05)';
    
    const files = event.dataTransfer.files;
    
    if (files.length > 0) {
        const validExtensions = ['.pdf', '.doc', '.docx', '.xls', '.xlsx', '.ppt', '.pptx', '.zip'];
        let allValid = true;
        
        for (let file of files) {
            const extension = '.' + file.name.split('.').pop().toLowerCase();
            if (!validExtensions.includes(extension)) {
                allValid = false;
                break;
            }
        }
        
        if (!allValid) {
            showNotification('Tipe file tidak didukung. Gunakan: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP', 'error');
        } else {
            showNotification(`${files.length} file siap untuk diunggah. Silakan isi form dan klik tombol Upload.`, 'success');
        }
    }
}

// ============================================
// NOTIFICATION SYSTEM
// ============================================

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    
    const icons = {
        success: '✓',
        error: '✕',
        info: 'ℹ',
        warning: '⚠'
    };
    
    const colors = {
        success: '#28a745',
        error: '#dc3545',
        info: '#007bff',
        warning: '#ffc107'
    };
    
    notification.innerHTML = `
        <div style="display: flex; align-items: center; gap: 1rem;">
            <span style="font-size: 1.5rem; color: ${colors[type]};">${icons[type]}</span>
            <span>${message}</span>
        </div>
    `;
    
    notification.style.cssText = `
        position: fixed;
        top: 80px;
        right: 20px;
        background-color: white;
        border: 2px solid ${colors[type]};
        border-radius: 8px;
        padding: 1rem 1.5rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 1000;
        max-width: 400px;
        animation: slideIn 0.3s ease-out;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

// ============================================
// ACTIVE NAV LINK
// ============================================

const navLinks = document.querySelectorAll('.nav-menu a');

navLinks.forEach(link => {
    link.addEventListener('click', function(event) {
        if (this.href.includes('#')) {
            navLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        }
    });
});

// Automatically update active nav link on scroll
window.addEventListener('scroll', () => {
    let current = '';
    const sections = document.querySelectorAll('section');
    
    sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.clientHeight;
        
        if (pageYOffset >= sectionTop - 200) {
            current = section.getAttribute('id');
        }
    });
    
    navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === `#${current}`) {
            link.classList.add('active');
        }
    });
});

// ============================================
// DOCUMENT ACTIONS (PREVIEW & DOWNLOAD)
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    const downloadBtns = document.querySelectorAll('.doc-actions .btn-primary');
    const previewBtns = document.querySelectorAll('.doc-actions .btn-outline');
    
    downloadBtns.forEach(btn => {
        btn.addEventListener('click', function(event) {
            event.stopPropagation();
            const docTitle = this.closest('.doc-card').querySelector('h3').textContent;
            showNotification(`Mendownload "${docTitle}"...`, 'info');
            
            // Simulate download
            setTimeout(() => {
                showNotification(`"${docTitle}" berhasil diunduh!`, 'success');
            }, 1000);
        });
    });
    
    previewBtns.forEach(btn => {
        btn.addEventListener('click', function(event) {
            event.stopPropagation();
            const docTitle = this.closest('.doc-card').querySelector('h3').textContent;
            showNotification(`Membuka preview "${docTitle}"...`, 'info');
        });
    });
});

// ============================================
// HAMBURGER MENU FOR MOBILE
// ============================================

function toggleMobileMenu() {
    const navMenu = document.querySelector('.nav-menu');
    navMenu.classList.toggle('active');
}

// ============================================
// KEYBOARD SHORTCUTS
// ============================================

document.addEventListener('keydown', function(event) {
    // Ctrl/Cmd + K for search
    if ((event.ctrlKey || event.metaKey) && event.key === 'k') {
        event.preventDefault();
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.focus();
        }
    }
    
    // Escape to clear search
    if (event.key === 'Escape') {
        const searchInput = document.getElementById('searchInput');
        if (searchInput && searchInput === document.activeElement) {
            searchInput.value = '';
            filterDocuments();
        }
    }
});

// ============================================
// CSS ANIMATIONS
// ============================================

const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// ============================================
// INITIALIZE ON LOAD
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    console.log('Sistem Manajemen Dokumen - Initialized');
    
    // Set current year in year filter and input
    const currentYear = new Date().getFullYear();
    const docYearInput = document.getElementById('docYear');
    if (docYearInput) {
        docYearInput.value = currentYear;
    }

    // Close sidebar when clicking outside
    document.addEventListener('click', function(event) {
        const sidebar = document.querySelector('.sidebar');
        const menuToggle = document.querySelector('.menu-toggle');
        const sidebarHeader = document.querySelector('.sidebar-header');
        
        if (sidebar && sidebar.classList.contains('active')) {
            if (!sidebar.contains(event.target) && !menuToggle.contains(event.target)) {
                closeSidebar();
            }
        }
    });

    // Close sidebar on escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeSidebar();
        }
    });
});

// ============================================
// LOCAL STORAGE FOR RECENT UPLOADS
// ============================================

function saveRecentUpload(docData) {
    let recentUploads = JSON.parse(localStorage.getItem('recentUploads') || '[]');
    recentUploads.unshift(docData);
    
    // Keep only last 10 uploads
    if (recentUploads.length > 10) {
        recentUploads = recentUploads.slice(0, 10);
    }
    
    localStorage.setItem('recentUploads', JSON.stringify(recentUploads));
}

function getRecentUploads() {
    return JSON.parse(localStorage.getItem('recentUploads') || '[]');
}

// ============================================
// PRINT FUNCTIONALITY
// ============================================

function printDocument(docTitle) {
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>Laporan - ${docTitle}</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 2rem; }
                    .header { border-bottom: 2px solid #007bff; padding-bottom: 1rem; margin-bottom: 2rem; }
                    .header h1 { margin: 0; }
                    .content { line-height: 1.6; }
                    .footer { border-top: 1px solid #ddd; margin-top: 3rem; padding-top: 1rem; text-align: center; color: #666; font-size: 0.9rem; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>${docTitle}</h1>
                    <p>Dicetak pada: ${new Date().toLocaleDateString('id-ID')}</p>
                </div>
                <div class="content">
                    <p>Dokumen ini dicetak dari Sistem Manajemen Dokumen</p>
                </div>
                <div class="footer">
                    <p>© 2026 Rumah Pendukung Layanan Dukungan Manajemen</p>
                </div>
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}
