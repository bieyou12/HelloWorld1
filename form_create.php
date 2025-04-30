<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" nonce="<?php echo $this->nonce; ?>">
</head>
<div class="content-wrapper">
    <div class="content">


    <div class="container-fluid ">
    <div class="card card-rounded p-3 overflow-hidden">

        <div class="row">

            <!-- Sidebar with static fields -->
            <div class="col-md-3">
                <div class="card  mb-2 top-0">
                    <div class="card-header card-header-black">
                        <h5><i class="bi bi-gear-wide"></i> Form Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="form-name" class="form-label">Form Name</label>
                            <!-- <input type="text" class="form-control" id="form-name"  value="<?php echo $form['form_name']; ?>" disabled> -->
                            <textarea class="form-control mt-2" id="form-description" rows="3" placeholder="Form Description" disabled><?php echo $form['form_name']; ?></textarea>


                        </div>
                        <button id="save-form-btn" class="btn btn-primary w-100">Save Field Form</button>
                    </div>
                </div>

                <div class="sidebar">
                    <div class="card">
                        <div class="card-header card-header-black d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="bi bi-person-circle"></i> Static Fields</h5>
                            <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#staticFieldsCollapse" aria-expanded="true">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>
                        <div id="staticFieldsCollapse" class="collapse show">
                            <div class="card-body">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="staticFieldSearch" placeholder="Search fields...">
                                    <button class="btn btn-outline-secondary" type="button" id="showAllStatic">Show All</button>
                                </div>
                                <div id="static-fields">
                                    <?php foreach ($static_fields as $index => $field): ?>
                                        <div class="static-field" data-field='<?= json_encode($field) ?>' data-field-type="<?= $field['type'] ?>_<?= $index ?>" data-field-name="<?= $field['name'] ?>">
                                            <i class="fas fa-plus-circle me-2"></i><?= $field['label'] ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="sidebar my-3">
                    <div class="card">
                        <div class="card-header card-header-black d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="bi bi-person-circle"></i> Dynamic Fields</h5>
                            <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#dynamicFieldsCollapse" aria-expanded="true">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>
                        <div id="dynamicFieldsCollapse" class="collapse show">
                            <div class="card-body">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="dynamicFieldSearch" placeholder="Search fields...">
                                    <button class="btn btn-outline-secondary" type="button" id="showAllDynamic">Show All</button>
                                </div>
                                <div id="dynamic-fields">
                                    <?php foreach ($dynamic_fields as $index => $field): ?>
                                        <div class="static-field" data-field='<?= json_encode($field) ?>' data-field-type="dynamic_<?= $field['type'] ?>_<?= $index ?>">
                                            <i class="fas <?= $field['icon'] ?> me-2"></i><?= $field['label'] ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-9">
                <div class="row">

                <!-- Main form builder area -->
                    <div class="col-md-12 mb-3">
                        <div class="card">
                            <div class="card-header card-header-black d-flex justify-content-between align-items-center">
                                <h5><i class="bi bi-card-heading"></i> Form Builder</h5>
                                <!-- <button id="add-field-btn" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus me-1"></i> Add Field
                                </button> -->
                                <!-- <button id="clipboard" class="btn btn-success btn-sm">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                                <button id="url_register" class="btn btn-success btn-sm">
                                    <i class="bi bi-box-arrow-up-right"></i> Open Link
                                </button> -->
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                     <button id="clipboard" class="btn btn-primary me-md-1" type="button"> <i class="bi bi-clipboard"></i> </button>
                                     <button id="url_register" class="btn btn-light" type="button"><i class="bi bi-box-arrow-up-right"></i> Open Link</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="form-fields-container" class="form-fields-sortable">
                                    <!-- Dynamic form fields will be added here -->
                                </div>

                                <div class="text-center mt-3">
                                    <button id="generate-form-btn" class="btn btn-primary">
                                        <i class="fas fa-magic me-1"></i> Generate Form
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form preview area -->
                    <div class=" col-md-12">
                        <div class="card">
                            <div class="card-header card-header-black">
                                <h5><i class="bi bi-play-btn"></i> Form Preview</h5>
                            </div>
                            <div class="card-body">
                                <div id="preview-form-container" class="preview-form">
                                    <form id="preview-form">
    <!-- เพิ่มข้อมูล form_id ที่ซ่อนไว้ -->
    <input type="hidden" id="form-id" value="<?php echo $form['form_no']; ?>">
    <input type="hidden" name="form_id" value="<?php echo $form['form_no']; ?>" />
    <input type="hidden" name="eventID" value="<?php echo $form['eventID']; ?>" />
    <input type="hidden" name="subdomain" value="<?php echo $form['subdomain']; ?>" />
    <input type="hidden" name="isPreWalk" value="<?php echo $form['isPreWalk']; ?>" />
    <input type="hidden" name="registerType" value="<?php echo $form['registerType']; ?>" />
    <input type="hidden" name="form_label_lang" value="<?php echo $form['form_label_lang']; ?>" />  <!-- EN, TH, EN_TH, TH_EN -->
    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                        <div id="preview-fields-container">

                                            <!-- Preview fields will be shown here -->
                                            <p class="text-center text-muted">Click "Generate Form" to see the preview</p>
                                        </div>

                                        <div class="mt-4 text-center">
                                            <button type="button" class="btn btn-primary" disabled>Submit Form</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


        </div>
        </div>

    </div>

    <!-- Field template (hidden) -->
    <div id="field-template" class="d-none">
        <div class="field-item" data-field-id="">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <span class="handle"><i class="fas fa-grip-vertical"></i></span>
                    <span class="field-label">New Field</span>
                </div>
                <div>
                    <button class="btn btn-sm btn-outline-primary edit-field-btn">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger remove-field-btn">
                        <i class="fas fa-trash"></i> 
                    </button>
                </div>
            </div>

            <!-- Original field name will be stored here -->
            <input type="hidden" class="original-field-label">
            <small class="text-muted mb-2 d-block">
                <i class="bi bi-info-circle"></i> Original Field : 
                <span class="previous-field-name text-primary"></span>
            </small>

            <div class="field-editor mt-2">
                
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Field Name</label>
                        <input type="text" class="form-control field-name" placeholder="Enter field name">
                        <input type="hidden" class="original-field-name">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Field Name Thai</label>
                        <input type="text" class="form-control field-name-thai" placeholder="ชื่อฟิลด์ภาษาไทย">
                    </div>
                   
                    <div class="col-md-6">
                        <label class="form-label">Field Type</label>
                        <select class="form-select field-type">
                            <?php foreach ($field_types as $value => $label): ?>
                                <option value="<?= $value ?>"><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Required Value</label>
                        <input type="text" class="form-control field-required-value" placeholder="Enter required value">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Default Value</label>
                        <input type="text" class="form-control field-default" placeholder="Enter default value">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Placeholder</label>
                        <input type="text" class="form-control field-placeholder" placeholder="Enter placeholder text">
                    </div>
                    
                </div>

                <div class="options-container mb-3" style="display:none;">
                    <label class="form-label">Options</label>
                    <div class="options-list">
                        <!-- Options will be added here dynamically -->
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary mt-2 add-option-btn">
                        <i class="fas fa-plus"></i> Add Option
                    </button>
                </div>

                <!-- Option item template (hidden) -->
                <div id="option-template" class="d-none">
                    <div class="option-item mb-2">
                        <div class="row g-2 align-items-center">
                            <div class="col-auto">
                                <i class="fas fa-grip-vertical handle-option"></i>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control form-control-sm option-value" placeholder="Option value">
                            </div>
                            <div class="col-auto">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input option-required">
                                    <label class="form-check-label">Required</label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input option-show-input" onchange="toggleSubInput(this)">
                                    <label class="form-check-label">Show input</label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input option-default" name="default">
                                    <label class="form-check-label">Default</label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-option-btn">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="sub-input-container mt-2" style="display:none;">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm sub-input-placeholder" placeholder="Input label">
                                <select class="form-select form-select-sm sub-input-type" style="max-width: 120px;">
                                    <option value="text">Text</option>
                                    <option value="number">Number</option>
                                    <option value="email">Email</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input field-required" type="checkbox" id="">
                            <label class="form-check-label">Required Field</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input field-full-width" type="checkbox" id="">
                            <label class="form-check-label">Full Width</label>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input field-onsite" type="checkbox" id="" checked>
                            <label class="form-check-label">Show on Website</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input field-export" type="checkbox" id="" checked>
                            <label class="form-check-label">Include in Export</label>
                        </div>
                    </div>
                </div>

                <!-- Language Registration Type Settings -->
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Language</label>
                        <select class="form-select field-language">
                            <option value="ALL">All Languages</option>
                            <option value="TH">Thai Only</option>
                            <option value="EN">English Only</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Input Format</label>
                        <select class="form-select field-input-format">
                            <option value="DEFAULT">Default</option>
                            <option value="UPPER">Uppercase</option>
                            <option value="LOWER">Lowercase</option>
                            <option value="UCWORDS">UCWORDS</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Registration Type</label>
                        <select class="form-select field-registration-type">
                            <option value="ALL">All Types</option>
                            <option value="PRE">Pre-Registration</option>
                            <option value="WALK">Walk-in</option>
                        </select>
                    </div>
                </div>


                <!-- Display Settings -->
                <div class="row g-3 mb-3">
                    <div class="col-12">
                        <label class="form-label">Display On</label>
                        <div class="d-flex gap-3">
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input field-display" value="ONSITE" checked>
                                <label class="form-check-label">Onsite</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input field-display" value="KIOSK">
                                <label class="form-check-label">Kiosk</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input field-display" value="CMS">
                                <label class="form-check-label">CMS</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input field-display" value="EXHIBITOR">
                                <label class="form-check-label">Exhibitor</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Validation Settings -->
                <div class="row g-3 mb-3 validation-settings" style="display: none;">
                    <div class="col-md-6">
                        <label class="form-label">Validation Type</label>
                        <select class="form-select field-validation">
                            <option value="NONE">None</option>
                            <option value="EMAIL">Email</option>
                            <option value="MOBILE">Mobile</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check mt-4">
                            <input type="checkbox" class="form-check-input field-unique">
                            <label class="form-check-label">Check Duplicate Values</label>
                        </div>
                    </div>
                </div>

                
            </div>
        </div>
    </div>


    </div>
    </div>


    
    <script  nonce="<?php echo $this->nonce; ?>">
        $(document).ready(function() {
            let fieldCounter = 0;

            // เพิ่มตัวแปรเก็บ fields ที่เลือกไว้
            let selectedFields = new Set();

            // Make fields sortable
            $("#form-fields-container").sortable({
                handle: ".handle",
                update: function() {
                    updatePreview();
                }
            });

            // Add new field button
            $("#add-field-btn").click(function() {
                addNewField();
            });

            // Add static field with selection tracking
            $(document).on("click", ".static-field", function() {
                const fieldData = $(this).data('field');
                const fieldType = $(this).data('field-type');
                
                if (!fieldType.startsWith('dynamic_')) {
                    if (!$(this).hasClass('selected')) {
                        const fieldItem = addStaticField(fieldData);
                        fieldItem.attr('data-field-type', fieldType);
                        $(this).addClass('selected');
                        $(this).find('i.fas').removeClass('fa-plus-circle').addClass('fa-check-circle');
                        
                        // Only lock field type for country/nationality
                        const fieldEditor = fieldItem.find('.field-editor');
                        if (fieldData.special_type === 'country' || fieldData.special_type === 'nationality') {
                            fieldEditor.find('.field-type')
                                .val('select')
                                .prop('disabled', true);
                            
                            // Special handling for Country and Nationality
                            const optionsContainer = fieldEditor.find('.options-container');
                            optionsContainer.show();
                            optionsContainer.find('.options-list, .add-option-btn').hide();
                            
                            // Remove any existing readonly-options to prevent duplication
                            optionsContainer.find('.readonly-options').remove();
                            
                            // Create a sortable container for country/nationality options
                            const sortableOptionsContainer = $('<div class="country-options-container"></div>');
                            
                            // Parse the field data (either from original or from saved JSON)
                            let optionsArray = [];
                            if (fieldData.options) {
                                if (typeof fieldData.options === 'string' && fieldData.options.startsWith('[{')) {
                                    try {
                                        optionsArray = JSON.parse(fieldData.options);
                                    } catch (e) {
                                        console.error('Error parsing options JSON', e);
                                        optionsArray = fieldData.options;
                                    }
                                } else {
                                    // Convert simple array to object format if needed
                                    if (Array.isArray(fieldData.options) && fieldData.options.length > 0 && typeof fieldData.options[0] === 'string') {
                                        optionsArray = fieldData.options.map((value, index) => ({
                                            id: index + 1,
                                            value: value,
                                            show: true,
                                            orderby: index + 1
                                        }));
                                    }
                                }
                                
                                // Sort by orderby if available
                                if (optionsArray.length > 0 && optionsArray[0].orderby) {
                                    optionsArray.sort((a, b) => a.orderby - b.orderby);
                                }
                            }
                            
                            // Create option items
                            if (Array.isArray(optionsArray) && optionsArray.length) {
                                optionsArray.forEach(option => {
                                    // For object format
                                    if (typeof option === 'object') {
                                        const value = option.value;
                                        const isShown = option.show === true || option.show === 'true' || option.show === 1 || option.show === "1";
                                        const id = option.id;
                                        
                                        const optionItem = $(`
                                            <div class="country-option-item mb-2" data-id="${id}" data-value="${value}" data-order="${option.orderby || index + 1}">
                                                <div class="row g-2 align-items-center">
                                                    <div class="col-auto">
                                                        <i class="fas fa-grip-vertical handle-country-option"></i>
                                                    </div>
                                                    <div class="col">
                                                        <input type="text" class="form-control form-control-sm country-option-value" value="${value}" readonly>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input country-option-show" ${isShown ? 'checked' : ''}>
                                                            <label class="form-check-label">Show</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        `);
                                        sortableOptionsContainer.append(optionItem);
                                    }
                                });
                            }
                            
                            optionsContainer.append(sortableOptionsContainer);
                            
                            // Make country options sortable
                            sortableOptionsContainer.sortable({
                                handle: ".handle-country-option",
                                axis: "y",
                                update: function(event, ui) {
                                    // Update order numbers after sorting
                                    $(this).find('.country-option-item').each(function(index) {
                                        $(this).attr('data-order', index + 1);
                                    });
                                }
                            });
                        }
                        
                        // Show/hide validation settings
                        const fieldLabel = fieldData.label.toLowerCase();
                        const validationContainer = fieldItem.find('.validation-settings');
                        const validationSelect = validationContainer.find('.field-validation');
                        
                        if (fieldLabel === 'mobile') {
                            validationContainer.show();
                            validationSelect.val('MOBILE');
                        } else if (fieldLabel === 'email') {
                            validationContainer.show();
                            validationSelect.val('EMAIL');
                        } else {
                            validationContainer.hide();
                            validationSelect.val('NONE');
                        }
                    }
                } else {
                    const newField = addStaticField(fieldData);
                    newField.attr('data-field-type', fieldType);
                    
                    // Show options immediately for fields that need them
                    const type = fieldData.type;
                    const fieldEditor = newField.find('.field-editor');
                    const optionsContainer = fieldEditor.find('.options-container');
                    const optionsList = optionsContainer.find(".options-list");
                    const isSingleCheckbox = fieldData.type === 'single-checkbox';
                    
                    // Hide options container for text and textarea fields
                    if (type === 'text' || type === 'textarea') {
                        optionsContainer.hide();
                        return;
                    }
                    
                    optionsContainer.show();
                    optionsList.empty();
                    
                    if (isSingleCheckbox) {
                        // กรณี single-checkbox ให้มี option เดียว
                        addOptionItem(optionsList);
                        optionsContainer.find('.add-option-btn').hide();
                        newField.find('.field-type').val('checkbox');
                    } else {
                        // กรณีอื่นๆ
                        if (!fieldData.options || !fieldData.options.length) {
                            addOptionItem(optionsList);
                            addOptionItem(optionsList);
                        } else {
                            fieldData.options.forEach(option => {
                                addOptionItem(optionsList);
                                optionsList.find(".option-value:last").val(option);
                            });
                        }
                    }
                    
                    optionsList.sortable({
                        handle: ".handle-option",
                        axis: "y"
                    });
                }
            });

            // Generate form preview
            $("#generate-form-btn").click(function() {
                updatePreview();
            });

            // Remove field button
            $(document).on("click", ".remove-field-btn", function() {
                const fieldItem = $(this).closest(".field-item");
                const fieldType = fieldItem.data("field-type");
                const fieldData = fieldItem.data('field');
                const formID = $('#form-id').val();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Delete from database
                        $.ajax({
                            url: "<?= site_url('formbuilder/delete_field') ?>",
                            type: "POST",
                            data: {
                                form_id: formID,
                                field_name: fieldData ? fieldData.name : fieldItem.find(".field-name").val(),
                                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                            },
                            dataType: "json",
                            success: function(response) {
                                if (response.status) {
                                    // Find matching static field by name instead of type
                                    const fieldName = fieldData ? fieldData.name : fieldItem.find(".field-name").val();
                                    const staticField = $(`.static-field[data-field-name="${fieldName}"]`);
                                    // Reset static field state if found
                                    if (staticField.length > 0) {
                                        staticField
                                            .removeClass('selected')
                                            .css({
                                                'pointer-events': 'auto',
                                                'opacity': '1'
                                            })
                                            .find('i.fas')
                                            .removeClass('fa-check-circle')
                                            .addClass('fa-plus-circle');
                                    }
                                    // Remove from selected fields set
                                    selectedFields.delete(fieldName);
                                    // Remove field and update preview
                                    fieldItem.remove();
                                    updatePreview();
                                    Swal.fire(
                                        'Deleted!',
                                        'Field has been deleted.',
                                        'success'
                                    );
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        response.message,
                                        'error'
                                    );
                                }
                            },
                            error: function() {
                                Swal.fire(
                                    'Error!',
                                    'An error occurred while deleting the field.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            // Save form button
            $("#save-form-btn").click(function() {
                const formFields = [];
                const formID = $('#form-id').val();

                // Show loading state
                let timerInterval;
                Swal.fire({
                    title: 'Saving form...',
                    html: 'Please wait...',
                    timer: 0,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                    willClose: () => {
                        clearInterval(timerInterval);
                    },
                    allowOutsideClick: false
                });

                // Collect form fields
                $("#form-fields-container .field-item").each(function() {
                    const field = $(this);
                    const fieldData = field.data('field');
                    const fieldType = field.find(".field-type").val();

                    // Format options for country/nationality
                    let formattedOptions = null;
                    if (fieldData && (fieldData.special_type === 'country' || fieldData.special_type === 'nationality')) {
                        const optionsContainer = field.find('.country-options-container');
                        const optionsData = [];
                        
                        optionsContainer.find('.country-option-item').each(function(index) {
                            const item = $(this);
                            const isShown = item.find('.country-option-show').prop('checked');
                            
                            optionsData.push({
                                id: parseInt(item.data('id')),
                                value: item.find('.country-option-value').val(),
                                show: Boolean(isShown),
                                orderby: parseInt(item.attr('data-order'))
                            });
                        });
                        
                        formattedOptions = optionsData;
                    }

                    formFields.push({
                        id: field.attr("data-field-id"),
                        name: fieldData ? fieldData.name : field.find(".field-name").val(),
                        type: fieldType,
                        label: field.find(".field-name").val(),
                        label_th: field.find(".field-name-thai").val(),
                        placeholder: field.find(".field-placeholder").val(),
                        default: field.find(".field-default").val(),
                        required: field.find(".field-required").prop("checked"),
                        required_value: field.find(".field-required-value").val(),
                        fullWidth: field.find(".field-full-width").prop("checked"),
                        onsite: field.find(".field-onsite").prop("checked"),
                        export: field.find(".field-export").prop("checked"),
                        OriginalName: fieldData ? (fieldData.OriginalName || fieldData.name || fieldData.label) : field.find(".field-name").val(),
                        original_name: fieldData ? (fieldData.OriginalName || fieldData.name || fieldData.label) : field.find(".field-name").val(),
                        options: formattedOptions,
                        special_type: fieldData ? fieldData.special_type : null,
                        conf_field_is_choice: (fieldData && (fieldData.special_type === 'country' || fieldData.special_type === 'nationality')) ? 'YES' : 'NO',
                        conf_field_list_choice: formattedOptions ? JSON.stringify(formattedOptions) : null
                    });
                });

                $.ajax({
                    url: "<?= site_url('formbuilder/save_fields_form') ?>",
                    type: "POST",
                    data: {
                        form_id: formID,
                        form_data: formFields,
                        isPreWalk: $('input[name="isPreWalk"]').val(),
                        registerType: $('input[name="registerType"]').val(),
                        eventID: $('input[name="eventID"]').val(),
                        '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Form has been saved successfully',
                                icon: 'success',
                                showCancelButton: true,
                                confirmButtonText: 'Back to Form',
                                cancelButtonText: 'OK',
                                cancelButtonColor: '#28a745',
                                reverseButtons: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Redirect to forms list or do something else
                                    window.location.href = "<?= site_url('formbuilder') ?>";
                                }
                                // If canceled, simply close the SweetAlert
                            });
                        
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message || 'Failed to save form',
                                icon: 'error',
                                confirmButtonText: 'Try Again',
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred while saving the form. Please try again.',
                            icon: 'error',
                            showCancelButton: true,
                            confirmButtonText: 'Try Again',
                            cancelButtonText: 'Back to Form'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Try saving again
                                $("#save-form-btn").click();
                            }
                        });
                    }
                });
            });

            function addNewField(fieldData = null) {
                fieldCounter++;
                const fieldId = "field_" + Date.now() + "_" + fieldCounter;

                const newField = $("#field-template").children().clone();

                newField.attr("data-field-id", fieldId);

                newField.find(".field-editor").hide();

                if (fieldData) {
                    newField.find(".field-label").text(fieldData.label);
                    newField.find(".field-name").val(fieldData.label);
                    newField.find(".field-name-thai").val(fieldData.label_th || '');
                    newField.find(".field-type").val(fieldData.type);
                    newField.find(".field-placeholder").val(fieldData.placeholder || '');
                    newField.find(".field-required-value").val(fieldData.required_value || '');

                    if (fieldData.type === 'daterange') {
                        newField.find(".field-type").val('date');
                        newField.addClass('date-range-field');
                    } else if (fieldData.type === 'image' || fieldData.type === 'video') {
                        newField.find(".field-type").val('file');
                        newField.find('input[type="file"]').attr('accept', fieldData.accept);
                    }
                }

                newField.find(".field-required").attr("id", "required_" + fieldId);
                newField.find(".field-required").next().attr("for", "required_" + fieldId);

                newField.find(".field-full-width").attr("id", "full_width_" + fieldId);
                newField.find(".field-full-width").next().attr("for", "full_width_" + fieldId);

                newField.find(".field-onsite").attr("id", "onsite_" + fieldId);
                newField.find(".field-onsite").next().attr("for", "onsite_" + fieldId);

                newField.find(".field-export").attr("id", "export_" + fieldId);
                newField.find(".field-export").next().attr("for", "export_" + fieldId);

                $("#form-fields-container").append(newField);

                if (fieldData && (fieldData.type === 'select' || fieldData.type === 'radio' || 
                    fieldData.type === 'checkbox')) {
                    newField.find(".options-container").show();
                }
            }

            function addStaticField(fieldData) {
                fieldCounter++;
                const fieldId = "field_" + Date.now() + "_" + fieldCounter;
                const newField = $("#field-template").children().clone();

                newField.attr("data-field-id", fieldId);
                newField.data('field', fieldData);

                newField.find(".field-editor").hide();

                if (fieldData) {
                    // Use OriginalName if available, otherwise fallback to name or label
                    const originalName = fieldData.OriginalName || fieldData.name || fieldData.label;
                    newField.find(".previous-field-name").text(originalName);
                    newField.find(".original-field-label").val(originalName);

                    const fieldLabel = fieldData.label + (fieldData.label_th ? ' | ' + fieldData.label_th : '');
                    newField.find(".field-label").text(fieldLabel);
                    newField.find(".field-name").val(fieldData.label);
                    newField.find(".original-field-name").val(fieldData.name);
                    newField.find(".field-name-thai").val(fieldData.label_th || '');
                    
                    // Show options container for dynamic fields with options
                    if (fieldData.type === 'select' || fieldData.type === 'checkbox' || 
                        fieldData.type === 'single-checkbox' || fieldData.type === 'radio') {
                        
                        const optionsContainer = newField.find(".options-container");
                        const optionsList = optionsContainer.find(".options-list");
                        const isSingleCheckbox = fieldData.type === 'single-checkbox';
                        
                        optionsContainer.show();
                        optionsList.empty();

                        if (isSingleCheckbox) {
                            // กรณี single-checkbox ให้มี option เดียว
                            addOptionItem(optionsList);
                            optionsContainer.find('.add-option-btn').hide();
                            newField.find('.field-type').val('checkbox');
                        } else {
                            // กรณีอื่นๆ
                            if (fieldData.options && fieldData.options.length > 0) {
                                fieldData.options.forEach(option => {
                                    addOptionItem(optionsList);
                                    optionsList.find(".option-value:last").val(option);
                                });
                            } else {
                                // Add two default empty options
                                addOptionItem(optionsList);
                                addOptionItem(optionsList);
                            }
                        }

                        // Make options sortable
                        optionsList.sortable({
                            handle: ".handle-option",
                            axis: "y"
                        });
                    }
                    
                    // Special handling for country/nationality fields
                    if (fieldData.special_type === 'country' || fieldData.special_type === 'nationality') {
                        const fieldTypeSelect = newField.find('.field-type');
                        fieldTypeSelect.val('select').prop('disabled', true);
                        
                        const optionsContainer = newField.find('.options-container');
                        optionsContainer.show();
                        optionsContainer.find('.options-list, .add-option-btn').hide();
                        
                        // Remove any existing containers
                        optionsContainer.find('.readonly-options, .country-options-container').remove();
                        
                        const sortableOptionsContainer = $('<div class="country-options-container"></div>');
                        let optionsArray = [];

                        // ดึงค่าจาก conf_field_list_choice ก่อน ถ้าไม่มีค่อยใช้ options
                        if (fieldData.conf_field_list_choice) {
                            try {
                                optionsArray = JSON.parse(fieldData.conf_field_list_choice);
                            } catch (e) {
                                console.error('Error parsing conf_field_list_choice:', e);
                                // ถ้าแปลงค่าไม่ได้ ให้ใช้ค่าจาก options แทน
                                optionsArray = fieldData.options || [];
                            }
                        } else if (fieldData.options) {
                            optionsArray = fieldData.options;
                        }

                        // ตรวจสอบและแปลงค่าให้อยู่ในรูปแบบที่ถูกต้อง
                        optionsArray = optionsArray.map((opt, index) => {
                            if (typeof opt === 'string') {
                                return {
                                    id: index + 1,
                                    value: opt,
                                    show: true,
                                    orderby: index + 1
                                };
                            }
                            return {
                                id: opt.id || index + 1,
                                value: opt.value || opt,
                                show: opt.show === true || opt.show === "true" || opt.show === 1 || opt.show === "1",
                                orderby: parseInt(opt.orderby) || index + 1
                            };
                        });

                        // เรียงลำดับตาม orderby
                        optionsArray.sort((a, b) => parseInt(a.orderby) - parseInt(b.orderby));
                        
                        // สร้าง options UI
                        optionsArray.forEach((option) => {
                            const isShown = option.show === true || option.show === "true" || option.show === 1 || option.show === "1";
                            const optionItem = $(`
                                <div class="country-option-item mb-2" 
                                     data-id="${option.id}" 
                                     data-value="${option.value}" 
                                     data-order="${option.orderby}">
                                    <div class="row g-2 align-items-center">
                                        <div class="col-auto">
                                            <i class="fas fa-grip-vertical handle-country-option"></i>
                                        </div>
                                        <div class="col">
                                            <input type="text" class="form-control form-control-sm country-option-value" 
                                                   value="${option.value}" readonly>
                                            </div>
                                            <div class="col-auto">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input country-option-show" 
                                                           ${isShown ? 'checked' : ''}>
                                                    <label class="form-check-label">Show</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `);
                                sortableOptionsContainer.append(optionItem);
                            });

                            optionsContainer.append(sortableOptionsContainer);
                            
                            // Make options sortable
                            sortableOptionsContainer.sortable({
                                handle: ".handle-country-option",
                                axis: "y",
                                update: function(event, ui) {
                                    $(this).find('.country-option-item').each(function(index) {
                                        $(this).attr('data-order', index + 1);
                                    });
                                }
                            });
                        }
                    }

                    $("#form-fields-container").append(newField);

                    return newField;
                }

            function addOptionItem(container) {
                const newOption = $("#option-template").children().clone();
                container.append(newOption);

                // Generate unique name for radio buttons
                const uniqueName = 'option_' + Date.now() + '_' + Math.floor(Math.random() * 1000);
                newOption.find('.option-default').attr('name', uniqueName);

                // Make sub-inputs work
                newOption.find('.option-show-input').on('change', function() {
                    $(this).closest('.option-item').find('.sub-input-container').slideToggle();
                });

                return newOption;
            }

            function updatePreview() {
                const previewContainer = $("#preview-fields-container");
                previewContainer.empty();

                const fields = [];
                $("#form-fields-container .field-item").each(function() {
                    const fieldItem = $(this);
                    const fieldId = fieldItem.data("field-id");
                    const originalFieldData = fieldItem.data('field');
                    const fieldType = fieldItem.find(".field-type").val();
                    const fieldName = fieldItem.find(".field-name").val() || "Unnamed Field";
                    const fieldNameThai = fieldItem.find(".field-name-thai").val();
                    const placeholder = fieldItem.find(".field-placeholder").val();
                    const defaultValue = fieldItem.find(".field-default").val();
                    const requiredValue = fieldItem.find(".field-required-value").val();
                    const isRequired = fieldItem.find(".field-required").prop("checked");
                    const isFullWidth = fieldItem.find(".field-full-width").prop("checked");
                    const showOnsite = fieldItem.find(".field-onsite").prop("checked");

                    if (!showOnsite) return;

                    let options = [];
                    if (fieldType === "select" || fieldType === "radio" || fieldType === "checkbox") {
                        // Get options from the options list items
                        fieldItem.find(".options-list .option-item").each(function() {
                            const optionValue = $(this).find(".option-value").val();
                            const showInput = $(this).find(".option-show-input").prop("checked");
                            const subInputPlaceholder = $(this).find(".sub-input-container input").attr("placeholder");
                            const subInputType = $(this).find(".sub-input-container select").val();
                            if (optionValue && optionValue.trim() !== "") {
                                options.push({
                                    value: optionValue.trim(),
                                    label: optionValue.trim(),
                                    showInput: showInput,
                                    subInputPlaceholder: subInputPlaceholder,
                                    subInputType: subInputType
                                });
                            }
                        });
                        
                        // If no options found in options list and it's a country/nationality field, use original options
                        if (options.length === 0 && originalFieldData && 
                            (originalFieldData.special_type === 'country' || originalFieldData.special_type === 'nationality')) {
                            options = originalFieldData.options;
                        }
                    }

                    fields.push({
                        id: fieldId,
                        type: fieldType,
                        name: fieldName,
                        name_th: fieldNameThai,
                        placeholder: placeholder,
                        default: defaultValue,
                        required: isRequired,
                        required_value: requiredValue,
                        fullWidth: isFullWidth,
                        options: options,
                        special_type: originalFieldData ? originalFieldData.special_type : null
                    });
                });

                let currentRow = null;

                fields.forEach(function(field, index) {
                    if (field.fullWidth) {
                        const fieldContainer = $(`<div class="row"><div class="col-12 mb-3"></div></div>`);
                        const fieldCol = fieldContainer.find('.col-12');
                        renderField(field, fieldCol);
                        previewContainer.append(fieldContainer);
                        currentRow = null;
                    } else {
                        if (!currentRow || currentRow.find('.col-md-6').length === 2) {
                            currentRow = $('<div class="row"></div>');
                            previewContainer.append(currentRow);
                        }
                        const fieldCol = $('<div class="col-md-6 mb-3"></div>');
                        renderField(field, fieldCol);
                        currentRow.append(fieldCol);
                    }
                });

                function renderField(field, container) {
                    const fieldLabel = $(`<label for="${field.id}" class="form-label">${field.name}${field.name_th ? ' | ' + field.name_th : ''}</label>`);
                    if (field.required) {
                        fieldLabel.append(' <span class="text-danger">*</span>');
                    }

                    container.append(fieldLabel);

                    let fieldElement;

                    switch (field.type) {
                        case 'text':
                        case 'email':
                        case 'password':
                        case 'tel':
                        case 'number':
                        case 'date':
                        case 'time':
                        case 'datetime-local':
                            fieldElement = $(`<input type="${field.type}" class="form-control" id="${field.id}" name="${field.id}" placeholder="${field.placeholder || ''}" value="${field.default || ''}" ${field.required ? 'required' : ''}>`);
                            break;

                        case 'textarea':
                            fieldElement = $(`<textarea class="form-control" id="${field.id}" name="${field.id}" placeholder="${field.placeholder || ''}" rows="3" ${field.required ? 'required' : ''}>${field.default || ''}</textarea>`);
                            break;

                        case 'select':
                            fieldElement = $(`<select class="form-select" id="${field.id}" name="${field.id}" ${field.required ? 'required' : ''}></select>`);

                            if (field.placeholder) {
                                fieldElement.append(`<option value="" disabled selected>${field.placeholder}</option>`);
                            }

                            field.options.forEach(function(optionText) {
                                let value, label, hasSubInput = false, subInputType = 'text', subInputLabel = '';
                                
                                if (typeof optionText === 'object') {
                                    value = optionText.value;
                                    label = optionText.label;
                                    hasSubInput = optionText.showInput;
                                    subInputType = optionText.subInputType || 'text';
                                    subInputLabel = optionText.subInputPlaceholder || 'Please specify...';
                                } else if (optionText.includes(':')) {
                                    [value, label] = optionText.split(':');
                                } else {
                                    value = label = optionText;
                                }

                                const option = $(`<option value="${value}" data-has-sub-input="${hasSubInput}" data-sub-input-type="${subInputType}" data-sub-input-label="${subInputLabel}">${label}</option>`);
                                if (field.default === value) {
                                    option.attr('selected', true);
                                }
                                fieldElement.append(option);
                            });
                            
                            // Add sub-input container if needed
                            const subInputContainer = $('<div class="mt-2" style="display:none;"></div>');
                            const subInput = $('<input type="text" class="form-control" placeholder="Please specify...">');
                            subInputContainer.append(subInput);
                            
                            // Handle sub-input visibility
                            fieldElement.on('change', function() {
                                const selectedOption = $(this).find('option:selected');
                                if (selectedOption.data('has-sub-input')) {
                                    const subInputType = selectedOption.data('sub-input-type') || 'text';
                                    const subInputLabel = selectedOption.data('sub-input-label') || 'Please specify...';
                                    subInput.attr('type', subInputType).attr('placeholder', subInputLabel);
                                    subInputContainer.slideDown();
                                } else {
                                    subInputContainer.slideUp();
                                }
                            });
                            
                            container.append(fieldElement, subInputContainer);
                            return;

                        case 'radio':
                            fieldElement = $('<div></div>');
                            
                            field.options.forEach(function(optionText, index) {
                                let value, label, hasSubInput = false, subInputType = 'text', subInputLabel = '';
                                
                                if (typeof optionText === 'object') {
                                    value = optionText.value;
                                    label = optionText.label;
                                    hasSubInput = optionText.showInput;
                                    subInputType = optionText.subInputType || 'text';
                                    subInputLabel = optionText.subInputPlaceholder || 'Please specify...';
                                } else if (optionText.includes(':')) {
                                    [value, label] = optionText.split(':');
                                } else {
                                    value = label = optionText;
                                }
                                
                                const radioId = `${field.id}_${index}`;
                                const radioContainer = $('<div class="form-check mb-2"></div>');
                                const radioInput = $(`<input class="form-check-input" type="radio" name="${field.id}" id="${radioId}" value="${value}" ${field.default === value ? 'checked' : ''} ${field.required ? 'required' : ''}>`);
                                const radioLabel = $(`<label class="form-check-label" for="${radioId}">${label}</label>`);
                                
                                radioContainer.append(radioInput, radioLabel);
                                
                                if (hasSubInput) {
                                    const subInputGroup = $('<div class="input-group mt-1" style="margin-left: 25px; width: calc(100% - 25px);"></div>');
                                    const subInput = $(`<input type="${subInputType}" class="form-control form-control-sm" placeholder="${subInputLabel}">`);
                                    subInputGroup.append(subInput);
                                    radioContainer.append(subInputGroup);
                                    radioInput.on('change', function() {
                                        subInput.prop('disabled', !this.checked);
                                    });
                                    subInput.prop('disabled', !radioInput.prop('checked'));
                                }
                                
                                fieldElement.append(radioContainer);
                            });
                            break;

                        case 'checkbox':
                            fieldElement = $('<div></div>');
                            
                            field.options.forEach(function(optionText, index) {
                                let value, label, hasSubInput = false, subInputType = 'text', subInputLabel = '';
                                
                                if (typeof optionText === 'object') {
                                    value = optionText.value;
                                    label = optionText.label;
                                    hasSubInput = optionText.showInput;
                                    subInputType = optionText.subInputType || 'text';
                                    subInputLabel = optionText.subInputPlaceholder || 'Please specify...';
                                } else if (optionText.includes(':')) {
                                    [value, label] = optionText.split(':');
                                } else {
                                    value = label = optionText;
                                }
                                
                                const checkboxId = `${field.id}_${index}`;
                                const checkboxContainer = $('<div class="form-check mb-2"></div>');
                                const checkboxInput = $(`<input class="form-check-input" type="checkbox" name="${field.id}[]" id="${checkboxId}" value="${value}">`);
                                const checkboxLabel = $(`<label class="form-check-label" for="${checkboxId}">${label}</label>`);
                                
                                checkboxContainer.append(checkboxInput, checkboxLabel);
                                
                                if (hasSubInput) {
                                    const subInputGroup = $('<div class="input-group mt-1" style="margin-left: 25px; width: calc(100% - 25px);"></div>');
                                    const subInput = $(`<input type="${subInputType}" class="form-control form-control-sm" placeholder="${subInputLabel}">`);
                                    subInputGroup.append(subInput);
                                    checkboxContainer.append(subInputGroup);
                                    checkboxInput.on('change', function() {
                                        subInput.prop('disabled', !this.checked);
                                    });
                                    subInput.prop('disabled', !checkboxInput.prop('checked'));
                                }
                                
                                fieldElement.append(checkboxContainer);
                            });
                            break;

                        case 'file':
                            fieldElement = $(`<input type="file" class="form-control" id="${field.id}" name="${field.id}" ${field.required ? 'required' : ''}>`);
                            break;

                        case 'hidden':
                            fieldElement = $(`<input type="hidden" id="${field.id}" name="${field.id}" value="${field.default || ''}">`);
                            break;
                    }

                    container.append(fieldElement);
                }

                if (fields.length > 0) {
                    $("#preview-form button").prop("disabled", false);
                } else {
                    previewContainer.html('<p class="text-center text-muted">Add fields to see the preview</p>');
                    $("#preview-form button").prop("disabled", true);
                }
            }

            // โหลด fields ที่มีอยู่เมื่อเริ่มต้น
            <?php if(isset($form_fields) && !empty($form_fields)): ?>
                const existingFields = <?php echo $form_fields; ?>;
                
                existingFields.forEach(function(field) {
                    // Find matching static field if exists
                    const staticField = $(`.static-field[data-field-name="${field.name}"]`);
                    const isStaticField = staticField.length > 0;

                    // Get original static field data if available
                    let originalFieldData = null;
                    if (isStaticField) {
                        originalFieldData = staticField.data('field');
                        selectedFields.add(field.name);
                    }

                    // Merge field data with original static field data
                    let fieldData = {
                        ...field,
                        type: field.type,
                        label: field.label,
                        label_th: field.label_th,
                        name: field.name,
                        OriginalName: originalFieldData ? originalFieldData.OriginalName : field.label,
                        placeholder: field.placeholder,
                        required: field.required,
                        required_value: field.required_value,
                        fullWidth: field.fullWidth,
                        options: field.options,
                        special_type: originalFieldData ? originalFieldData.special_type : null
                    };

                    // If it's a special field, ensure options are preserved
                    if (originalFieldData && 
                        (originalFieldData.special_type === 'country' || 
                         originalFieldData.special_type === 'nationality')) {
                        fieldData.options = originalFieldData.options;
                        fieldData.type = 'select';
                    }

                    const newField = addStaticField(fieldData);
                    
                    // Mark static field as selected if applicable
                    if (isStaticField) {
                        staticField
                            .addClass('selected')
                            .find('i.fas')
                            .removeClass('fa-plus-circle')
                            .addClass('fa-check-circle');
                    }
                });

                // Update disabled state of static fields
                selectedFields.forEach(fieldName => {
                    const staticField = $(`.static-field[data-field-name="${fieldName}"]`);
                    if (staticField.length > 0) {
                        staticField
                            .addClass('selected')
                            .css('pointer-events', 'none')
                            .css('opacity', '0.6');
                    }
                });
            <?php endif; ?>
        });

        function toggleSubInput(checkbox) {
            $(checkbox).closest('.option-item').find('.sub-input-container').slideToggle();
        }

        $(document).on('input', '.field-input', function() {
            const format = $(this).closest('.field-item').find('.field-input-format').val();
            let value = $(this).val();

            switch (format) {
                case 'UPPER':
                    value = value.toUpperCase();
                    break;
                case 'LOWER':
                    value = value.toLowerCase();
                    break;
            }

            $(this).val(value);
        });

        $("#staticFieldSearch").on("keyup", function() {
            let value = $(this).val().toLowerCase();
            $("#static-fields .static-field").filter(function() {
                let matches = $(this).text().toLowerCase().indexOf(value) > -1;
                $(this).toggle(matches);
            });
        });

        $("#dynamicFieldSearch").on("keyup", function() {
            let value = $(this).val().toLowerCase();
            $("#dynamic-fields .static-field").filter(function() {
                let matches = $(this).text().toLowerCase().indexOf(value) > -1;
                $(this).toggle(matches);
            });
        });

        $("#showAllStatic").click(function() {
            $("#staticFieldSearch").val('');
            $("#static-fields .static-field").show();
        });

        $("#showAllDynamic").click(function() {
            $("#dynamicFieldSearch").val('');
            $("#dynamic-fields .static-field").show();
        });

        $('.collapse').on('show.bs.collapse', function() {
            $(this).closest('.card').find('.fa-chevron-down').addClass('fa-rotate-180');
        });

        $('.collapse').on('hide.bs.collapse', function() {
            $(this).closest('.card').find('.fa-chevron-down').removeClass('fa-rotate-180');
        });

        $(document).on("click", ".edit-field-btn", function(e) {
            e.stopPropagation(); // ป้องกันการ bubble ขึ้นไปที่ field-item
            const fieldItem = $(this).closest('.field-item');
            const fieldEditor = fieldItem.find('.field-editor');
            // Toggle field editor with animation
            fieldEditor.slideToggle(300, function() {
                if (fieldEditor.is(':visible')) {
                    $('html, body').animate({
                        scrollTop: fieldItem.offset().top - 100
                    }, 500);
                }
            });
        });

        $(document).on("click", ".field-editor input, .field-editor select, .field-editor textarea", function(e) {
            e.stopPropagation(); // ป้องกันการ bubble
        });

        $(document).on("click", ".options-container", function(e) {
            e.stopPropagation(); // ป้องกันการ bubble
        });

        $(document).on("click", ".field-editor", function(e) {
            e.stopPropagation(); // ป้องกันการ bubble
        });

        $(document).on('click', '.edit-field-btn', function() {
            const fieldItem = $(this).closest('.field-item');
            const fieldData = fieldItem.data('field');
            if (fieldData && (fieldData.special_type === 'country' || fieldData.special_type === 'nationality')) {
                const optionsContainer = fieldItem.find('.options-container');
                optionsContainer.show();
                
                const optionsTextarea = optionsContainer.find('.field-options');
                if (fieldData.options && Array.isArray(fieldData.options)) {
                    optionsTextarea.val(fieldData.options.join('\n'));
                }
                optionsTextarea.prop('readonly', true);
            }
        });

        $(document).on('input', '.field-name, .field-name-thai', function() {
            const fieldItem = $(this).closest('.field-item');
            const fieldData = fieldItem.data('field');
            const originalName = fieldData ? (fieldData.OriginalName || fieldData.name || fieldData.label) : '';
            
            // คงค่าชื่อต้นฉบับไว้
            fieldItem.find(".previous-field-name").text(originalName);
            
            // อัพเดทชื่อที่แสดง
            const fieldName = fieldItem.find('.field-name').val();
            const fieldNameThai = fieldItem.find('.field-name-thai').val();
            const combinedLabel = fieldName + (fieldNameThai ? ' | ' + fieldNameThai : '');
            fieldItem.find('.field-label').text(combinedLabel);
        });

        function updatePreview() {
            fields.forEach(function(field) {
                if (field.special_type === 'country' || field.special_type === 'nationality') {
                    const originalField = $('.field-item[data-field-id="' + field.id + '"]').data('field');
                    if (originalField && originalField.options) {
                        field.options = originalField.options;
                    }
                }
            });
        }

        $(document).on('change', '.sub-input-type, .sub-input-placeholder', function() {
            const optionItem = $(this).closest('.option-item');
            const type = optionItem.find('.sub-input-type').val();
            const placeholder = optionItem.find('.sub-input-placeholder').val();
            updatePreview();
        });


    // copy URL to clipboard and open link
    $("#clipboard").on("click", function() {
        const subdomain = $('input[name="subdomain"]').val();
        const eventID = $('input[name="eventID"]').val();
        const url = `registration.eventpassinsight.co/e/${subdomain}/<?php echo $form['form_code']; ?>`;
        
        navigator.clipboard.writeText(url).then(function() {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'URL copied to clipboard!',
                showConfirmButton: false,
                timer: 1500
            });
        }).catch(function(err) {
            console.error('Failed to copy text: ', err);
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'Failed to copy URL',
                showConfirmButton: false,
                timer: 1500
            });
        });
    });

    $("#url_register").on("click", function() {
        const subdomain = $('input[name="subdomain"]').val();
        const eventID = $('input[name="eventID"]').val(); 
        const url = `https://registration.eventpassinsight.co/e/${subdomain}/<?php echo $form['form_code']; ?>`;
        window.open(url, '_blank');
    });
    </script>

<style nonce="<?php echo $this->nonce; ?>">
        .field-item {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .handle {
            cursor: move;
            padding: 5px;
            background-color: #e9ecef;
            border-radius: 3px;
            margin-right: 10px;
        }

        .static-field {
            cursor: pointer;
            padding: 8px;
            margin-bottom: 5px;
            background-color: #f0f0f0;
            border-radius: 3px;
            transition: background-color 0.3s;
        }

        .static-field:hover {
            background-color: #e0e0e0;
        }

        .preview-form {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
            background-color: white;
        }

        .sidebar {
            position: relative;
            top: 10px;
            padding: 0px 0 0; /* Height of navbar */
        }

        .options-container {
            display: none;
        }

        .static-field.selected {
            background-color: #e2e2e2;
            opacity: 0.6;
            pointer-events: none;
        }

        .static-field.selected i.fas {
            color: #28a745;
        }

        .fa-rotate-180 {
            transform: rotate(180deg);
            transition: transform 0.3s ease;
        }

        .btn-link {
            text-decoration: none;
            color: #6c757d;
            padding: 0;
        }

        .btn-link:hover {
            color: #000;
        }

        #static-fields, #dynamic-fields {
            max-height: 400px;
            overflow-y: auto;
        }
        .card-header-black{
            background-color: #000000;
        }
        .card-header-black h5{
            color: #ffffff;
        }

        .handle-option {
            cursor: move;
            color: #6c757d;
            padding: 5px;
        }

        .option-item {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 8px;
        }

        .options-list {
            min-height: 50px;
        }

        .readonly-options {
            /* background-color: #f8f9fa; */
            background: #03a9f41a;
            outline: 1px solid #0000002e;
            padding: 10px;
            border-radius: 4px;
            margin-top: 10px;
            overflow-y: scroll;
            height: 350px;
        }

        .readonly-options .form-control-plaintext {
            padding: 5px;
            border-bottom: 1px solid #dee2e6;
        }

        .readonly-options .form-control-plaintext:last-child {
            border-bottom: none;
        }

        .country-options-container {
            max-height: 400px;
            overflow-y: auto;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 4px;
        }

        .country-option-item {
            background: white;
            padding: 8px;
            margin-bottom: 5px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }

        .handle-country-option {
            cursor: move;
            color: #6c757d;
            padding: 5px;
        }
    </style>
