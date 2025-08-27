/**
 * Minimalist Search Enhancement
 * Enhances DataTables search and provides global search functionality
 */

$(document).ready(function() {
    // Enhance all DataTables with better search
    enhanceDataTablesSearch();
    
    // Initialize global search functionality
    initializeGlobalSearch();
    
    // Add keyboard shortcuts
    initializeKeyboardShortcuts();
});

/**
 * Enhance DataTables search with minimalist design
 */
function enhanceDataTablesSearch() {
    // Wait for DataTables to initialize
    setTimeout(function() {
        $('.dataTables_filter').each(function() {
            const $filterDiv = $(this);
            const $input = $filterDiv.find('input');
            
            if ($input.length) {
                // Add classes for styling
                $input.addClass('search-input-minimal');
                $filterDiv.addClass('search-container');
                
                // Add search icon
                if ($filterDiv.find('.search-icon-minimal').length === 0) {
                    $filterDiv.prepend('<i class="fa fa-search search-icon-minimal"></i>');
                }
                
                // Add clear button
                if ($filterDiv.find('.search-clear').length === 0) {
                    $filterDiv.append('<button type="button" class="search-clear" title="Clear search"><i class="fa fa-times"></i></button>');
                }
                
                // Update placeholder
                $input.attr('placeholder', 'Search records...');
                
                // Handle clear button
                $filterDiv.find('.search-clear').on('click', function() {
                    $input.val('').trigger('keyup');
                });
            }
        });
    }, 500);
}

/**
 * Initialize global search functionality
 */
function initializeGlobalSearch() {
    // Create global search if it doesn't exist
    if ($('#globalSearch').length === 0 && $('.search-input-minimal').length > 0) {
        addGlobalSearchBar();
    }
    
    // Handle search suggestions
    initializeSearchSuggestions();
}

/**
 * Add global search bar to admin tables
 */
function addGlobalSearchBar() {
    const searchHtml = `
        <div class="search-compact mb-3">
            <div class="search-container">
                <i class="fa fa-search search-icon-minimal"></i>
                <input type="text" id="globalSearch" class="search-input-minimal" placeholder="Search all records..." autocomplete="off">
                <button type="button" class="search-clear" title="Clear search"><i class="fa fa-times"></i></button>
                <div class="search-loading"><div class="spinner"></div></div>
                <div class="search-suggestions-minimal" id="searchSuggestions"></div>
            </div>
        </div>
    `;
    
    // Insert before the first table
    $('.table-responsive').first().before(searchHtml);
    
    // Handle global search
    $('#globalSearch').on('input', debounce(function() {
        const searchTerm = $(this).val();
        
        // Update all DataTables
        $('.data-table').each(function() {
            if ($.fn.DataTable.isDataTable(this)) {
                $(this).DataTable().search(searchTerm).draw();
            }
        });
        
        // Show/hide suggestions
        if (searchTerm.length > 2) {
            showSearchSuggestions(searchTerm);
        } else {
            hideSearchSuggestions();
        }
    }, 300));
    
    // Handle clear button
    $('.search-clear').on('click', function() {
        $('#globalSearch').val('').trigger('input');
    });
}

/**
 * Initialize search suggestions
 */
function initializeSearchSuggestions() {
    // Handle suggestion clicks
    $(document).on('click', '.suggestion-item', function() {
        const suggestion = $(this).text();
        $('#globalSearch').val(suggestion).trigger('input');
        hideSearchSuggestions();
    });
    
    // Hide suggestions when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.search-container').length) {
            hideSearchSuggestions();
        }
    });
}

/**
 * Show search suggestions
 */
function showSearchSuggestions(query) {
    const $suggestions = $('#searchSuggestions');
    const $loading = $('.search-loading');
    
    $loading.show();
    
    // Make AJAX call for suggestions
    $.ajax({
        url: '/search-suggestions',
        method: 'GET',
        data: { query: query },
        success: function(suggestions) {
            $loading.hide();
            
            if (suggestions && suggestions.length > 0) {
                const suggestionsHtml = suggestions.map(suggestion => 
                    `<div class="suggestion-item">${escapeHtml(suggestion)}</div>`
                ).join('');
                
                $suggestions.html(suggestionsHtml).show();
            } else {
                hideSearchSuggestions();
            }
        },
        error: function() {
            $loading.hide();
            hideSearchSuggestions();
        }
    });
}

/**
 * Hide search suggestions
 */
function hideSearchSuggestions() {
    $('#searchSuggestions').hide();
    $('.search-loading').hide();
}

/**
 * Initialize keyboard shortcuts
 */
function initializeKeyboardShortcuts() {
    $(document).on('keydown', function(e) {
        // Ctrl/Cmd + K for search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            $('#globalSearch, .dataTables_filter input').first().focus();
        }
        
        // Escape to clear search
        if (e.key === 'Escape') {
            const $activeSearch = $(':focus').closest('.search-container').find('input');
            if ($activeSearch.length) {
                $activeSearch.val('').trigger('input');
                hideSearchSuggestions();
            }
        }
    });
}

/**
 * Debounce function to limit API calls
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Escape HTML to prevent XSS
 */
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

/**
 * Create advanced search modal for complex queries
 */
function createAdvancedSearchModal() {
    const modalHtml = `
        <div class="modal fade" id="advancedSearchModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Advanced Search</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Search In</label>
                                    <select class="form-select" id="searchField">
                                        <option value="">All Fields</option>
                                        <option value="name">Name</option>
                                        <option value="email">Email</option>
                                        <option value="status">Status</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Date Range</label>
                                    <input type="date" class="form-control" id="dateFrom">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Search Term</label>
                            <input type="text" class="form-control search-input-minimal" id="advancedSearchTerm" placeholder="Enter search term...">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="performAdvancedSearch">Search</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    if ($('#advancedSearchModal').length === 0) {
        $('body').append(modalHtml);
    }
}

// Export functions for use in other scripts
window.SearchEnhancements = {
    enhanceDataTablesSearch: enhanceDataTablesSearch,
    showSearchSuggestions: showSearchSuggestions,
    hideSearchSuggestions: hideSearchSuggestions,
    debounce: debounce,
    escapeHtml: escapeHtml
};
