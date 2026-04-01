window.Luma = window.Luma || {};

Luma.CreateButton = function (targetSelector, config) {
    var text = config.text || "Button";
    var color = config.color || "primary";
    var id = config.id || "";
    var name = config.name || "";
    var data = config.data || {};
    var icon = config.icon || "";
    var iconPosition = config.iconPosition || "before";
    var customClass = config.class || "";

    var $button = $('<button></button>');

    if (customClass !== "") {
        $button.addClass(customClass);
    }
    else {
        $button.addClass('btn btn-' + color);
    }

    if (id !== "") {
        $button.attr('id', id);
    }

    if (name !== "") {
        $button.attr('name', name);
    }

    $.each(data, function (key, value) {
        $button.attr('data-' + key, value);
    });

    if (icon !== "") {
        var $icon = $('<i></i>').addClass(icon);

        if (iconPosition === "before") {
            $button.append($icon).append(' ' + text);
        } else {
            $button.append(text + ' ').append($icon);
        }
    } else {
        $button.text(text);
    }

    $(targetSelector).append($button);
};

Luma.CreateTitle = function (targetSelector, config) {
    var text = config.text || "Title";
    var color = config.color || "primary";
    var id = config.id || "";
    var name = config.name || "";
    var extraClass = config.class || ""; 

    // Create the title element
    var $title = $('<h3></h3>')
        .addClass('text-' + color)
        .addClass('text-uppercase')
        .addClass('fw-bold')
        .addClass(extraClass)
        .text(text);

    if (id !== "") {
        $title.attr('id', id);
    }

    if (name !== "") {
        $button.attr('name', name);
    }

    // Append to target
    $(targetSelector).append($title);
};

Luma.CreateDiv = function(targetSelector, config) {
    var id = config.id || "";
    var name = config.name || "";
    var divClass = config.class || "";

    var $div = $('<div></div>')
        .addClass(divClass);
    
    if (id !== "") {
        $div.attr('id', id);
    }

    if (name !== "") {
        $button.attr('name', name);
    }

    $(targetSelector).append($div);
};

Luma.CreateInput = function(targetSelector, config) {
    var id = config.id || "";
    var name = config.name || "";
    var type = config.type || "text"; // default to text if not specified
    var inputClass = config.class || "";
    var label = config.label || "";
    var labelClass = config.labelClass || ""; // optional custom label classes
    var placeholder = config.placeholder || "";
    var value = config.value || "";

    var $wrapper = $('<div class="mb-3"></div>'); // optional wrapper for spacing

    // Create label if provided
    if (label !== "") {
        var $label = $('<label></label>')
            .text(label)
            .addClass(labelClass)
            .addClass("form-label");

        if (id !== "") {
            $label.attr('for', id);
        }

        $wrapper.append($label);
    }

    // Create input element
    var $input = $('<input>')
        .addClass(inputClass)
        .attr('type', type);

    if (id !== "") {
        $input.attr('id', id);
    }

    if (name !== "") {
        $input.attr('name', name);
    }

    if (placeholder !== "") {
        $input.attr('placeholder', placeholder);
    }

    if (value !== "") {
        $input.attr('value', value);
    }

    $wrapper.append($input);

    // Append wrapper to target
    $(targetSelector).append($wrapper);
};

Luma.CreateTable = function(targetSelector, config) {
    var headers = config.headers || [];
    var body = config.data || [];
    var id = config.id || "";
    var name = config.name || "";
    var tableClass = config.class || "";

    var $table = $('<table></table>');
    var $tHead = $('<thead></thead>');
    var $headerRow = $('<tr></tr>');

    $.each(headers, function(index, value) {
        $headerRow.append(`<th>${value}</th>`);
    });

    if (id !== "") {
        $table.attr('id', id);
    }

    if (name !== "") {
        $table.attr('name', name);
    }

    if (tableClass !== "") {
        $table.addClass(tableClass);
    }

    $tHead.append($headerRow);
    $table.append($tHead);
    var $tBody = $('<tbody></tbody>');

    // Append data rows
    $.each(body, function(rowIndex, rowData) {
        var $row = $('<tr></tr>');
        $.each(rowData, function(colIndex, cellData) {
            $row.append(`<td>${cellData}</td>`);
        });
        $tBody.append($row);
    });

    $table.append($tBody);

    $(targetSelector).append($table);
};

