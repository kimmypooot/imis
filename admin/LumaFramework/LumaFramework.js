window.Luma = window.Luma || {};

Luma.RunQuery = function (queries) {
    if (!Array.isArray(queries)) {
        Swal.fire("Error", "Query must be an array of objects.", "error");
        return Promise.reject("Query must be an array of objects.");
    }

    for (const [i, queryObj] of queries.entries()) {
        if (typeof queryObj !== 'object' || !queryObj.query || typeof queryObj.query !== 'string') {
            Swal.fire("Error", `Query item at index ${i} is missing a valid 'query' string.`, "error");
            return Promise.reject(`Query item at index ${i} is missing a valid 'query' string.`);
        }

        if (!queryObj.params || typeof queryObj.params !== 'object') {
            Swal.fire("Error", `Query item at index ${i} is missing 'params' or it's not an object.`, "error");
            return Promise.reject(`Query item at index ${i} is missing 'params' or it's not an object.`);
        }

        for (const [key, value] of Object.entries(queryObj.params)) {
            if (value === undefined) {
                Swal.fire("Error", `Param '${key}' in query index ${i} is undefined.`, "error");
                return Promise.reject(`Param '${key}' in query index ${i} is undefined.`);
            }
        }
    }

    Swal.fire({
        title: 'Processing...',
        text: 'Please wait while we save your changes.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    const manilaTime = new Date().toLocaleString("en-US", { timeZone: "Asia/Manila" });
    const manilaDate = new Date(manilaTime);
    const key = (manilaDate.getHours() * 60 + manilaDate.getMinutes()) % 256;
    const encquery = Luma.EncryptData(queries, key);
    console.log(key);

    return $.ajax({
        url: "LumaFramework/LumaProcessData.php",
        type: "POST",
        dataType: "json",
        contentType: "application/json",
        data: JSON.stringify({ queries: encquery }),
    }).always(() => {
        Swal.close();
    });
}

Luma.GetDate = function (format = 'yyyy-mm-dd', date = new Date()) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0'); // month is 0-based
    const day = String(date.getDate()).padStart(2, '0');
    const hour = String(date.getHours()).padStart(2, '0');
    const minute = String(date.getMinutes()).padStart(2, '0');
    const second = String(date.getSeconds()).padStart(2, '0');

    // Replace format keywords
    return format
        .replace('yyyy', year)
        .replace('mm', month)
        .replace('dd', day)
        .replace('hh', hour)
        .replace('ii', minute)
        .replace('ss', second);
}

Luma.GetLocalDate = function (format = 'yyyy-mm-dd', date = new Date()) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0'); // month is 0-based
    const day = String(date.getDate()).padStart(2, '0');
    const hour = String(date.getHours()).padStart(2, '0');
    const minute = String(date.getMinutes()).padStart(2, '0');
    const second = String(date.getSeconds()).padStart(2, '0');

    return format
        .replace('yyyy', year)
        .replace('mm', month)
        .replace('dd', day)
        .replace('hh', hour)
        .replace('ii', minute)
        .replace('ss', second);
}

Luma.Insert =  function (query) {
    return Luma.RunQuery(query);
}

Luma.Update =  function (query) {
    return Luma.RunQuery(query);
}

Luma.Delete = function (query) {
    return Luma.RunQuery(query);
}

Luma.FormatDate = function (dateInput) {
    // Ensure the input is a valid date format, either a string or timestamp
    const date = new Date(dateInput);

    // Ensure it's a valid date
    if (isNaN(date)) {
        return null; // Return null if the date is invalid
    }

    const day = date.getDate();
    const month = date.toLocaleString('default', { month: 'long' }); // Full month name
    const year = date.getFullYear();

    // Check if the date includes time
    const hour = String(date.getHours()).padStart(2, '0');
    const minute = String(date.getMinutes()).padStart(2, '0');

    // Format the date
    if (date.getHours() === 0 && date.getMinutes() === 0) {
        // If no time, return full date in word format
        return `${month} ${day}, ${year}`;
    } else {
        // If time exists, return only hour and minute
        return `${month} ${day}, ${year} at ${hour}:${minute}`;
    }
}

//sample for simple query lumaDataTable(false, '#table', columns, 'my_table', 'timestamp', 'ASC')
//sample for custom query lumaDataTable('SELECT * FROM my_table', '#my_table', columns)
Luma.FetchTable = function (customQuery = null, tableId, columnConfigs, tabledb = null, orderBy = null, orderDirection = 'ASC') {
    // Build the query
    let query = '';

    if (customQuery) {
        query = customQuery; // If custom query is provided, use it directly
    } else {
        query = `SELECT * FROM ${tabledb}`; // Else, build query normally

        // If orderBy is provided, append the ORDER BY clause
        if (orderBy) {
            query += ` ORDER BY ${orderBy} ${orderDirection}`;
        }
    }

    const manilaTime = new Date().toLocaleString("en-US", { timeZone: "Asia/Manila" });
    const manilaDate = new Date(manilaTime);
    const key = (manilaDate.getHours() * 60 + manilaDate.getMinutes()) % 256;
    const encquery = Luma.EncryptData(query, key);

    // Initialize DataTable
    $(tableId).DataTable({
        "processing": true,
        "serverSide": false,
        "ordering": false,
        "ajax": {
            "url": "LumaFramework/LumaTable.php",
            "type": "POST",
            "data": function (d) {
                return { query: encquery };
            },
            "dataSrc": "data"
        },
        "columns": columnConfigs.map((col) => {
            return {
                "data": col.data,
                "render": col.render || (data => data)
            };
        })
    });
}

Luma.ReloadTable = function (table) {
    $(table).DataTable().destroy();
}

Luma.FetchSingleData = function(query) {
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: 'LumaFramework/LumaTable.php',
            type: 'POST',
            dataType: 'json',
            data: { query: query },
            success: function(response) {
                if (response.data && Array.isArray(response.data) && response.data.length > 0) {
                    resolve(response.data[0]); // Return the first row of data
                } else {
                    resolve(null); // No data found
                }
            },
            error: function(xhr, status, error) {
                console.error('FetchData error:', error);
                reject(error);
            }
        });
    });
}

Luma.Alert = function (title, response, icon, showCancel = false) {
    return Swal.fire({
        title: title,
        text: response,
        icon: icon,
        showCancelButton: showCancel,
        confirmButtonText: "OK",
        cancelButtonText: "Cancel"
    });
}

Luma.Transaction = function (queries) {
    return Luma.RunQuery(queries);
};

Luma.FetchData = function(payload) {
    const manilaTime = new Date().toLocaleString("en-US", { timeZone: "Asia/Manila" });
    const manilaDate = new Date(manilaTime);
    const key = (manilaDate.getHours() * 60 + manilaDate.getMinutes()) % 256;
    const encPayload = Luma.EncryptData(payload, key);
    console.log(key);

    return new Promise((resolve, reject) => {
        $.ajax({
            url: "LumaFramework/LumaFetchData.php",
            type: "POST",
            dataType: "json",
            contentType: "application/json",
            data: JSON.stringify({encPayload: encPayload}),
            success: function(response) {
                resolve(response.data);
            },
            error: function(xhr, status, error) {
                console.error('FetchData error:', error);
                reject(error);
            }
        });
    });
};

Luma.DropDown = function(query, dropdown, value, text) {
    Luma.FetchData(query).then(data => {
        $(`${dropdown}`).empty();
        $(`${dropdown}`).append(`<option value="" selected hidden>--- Select Option ---</option>`);
        data.forEach(item => {
            $(`${dropdown}`).append(`<option value="${item[value]}">${item[text]}</option>`);
        });
    });
};

Luma.Hash = function(value) {
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: 'LumaFramework/LumaHash.php',
            type: 'POST',
            dataType: 'json',
            data: { value: value },
            success: function(response) {
                resolve(response.data || null);
            },
            error: function(xhr, status, error) {
                console.error('Hash error:', error);
                reject(error);
            }
        });
    });
}

Luma.Verify = function(password, hashedPass) {
    return new Promise(function(resolve, reject) {
        Swal.fire({
            title: "Verifying...",
            text: "Please wait.",
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: 'LumaFramework/LumaVerify.php',
            type: 'POST',
            dataType: 'json',
            data: { password: password, hash: hashedPass },
            success: function(response) {
                Swal.close();
                resolve(response.data || null);
            },
            error: function(xhr, status, error) {
                Swal.close();
                console.error('Hash error:', error);
                reject(error);
            }
        });
    });
}

Luma.SetSession = function(sessionVars, clearSession) {
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: 'LumaFramework/LumaSetSession.php',
            type: 'POST',
            dataType: 'json',
            data: { sessionData: sessionVars , clearSession: clearSession},
            success: function(response) {
                resolve(response.success || false);
            },
            error: function(xhr, status, error) {
                console.error('Session error:', error);
                reject(error);
            }
        });
    });
}

Luma.Upload = function(config) {
    var fileInput = $(config.file)[0]; // get the actual DOM input
    var location = config.location;
    var fileName = config.name;

    const formData = new FormData();
    formData.append('fileToUpload', fileInput.files[0]); // <-- fixed
    formData.append('location', location);
    formData.append('fileName', fileName);

    return new Promise(function(resolve, reject) {
        $.ajax({
            url: 'LumaFramework/LumaUpload.php',
            type: 'POST',
            dataType: 'json',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                resolve(response || null);
            },
            error: function(xhr, status, error) {
                console.error('Upload error:', error);
                reject(error);
            }
        });
    });
}

Luma.EncryptData = function(data, key) {
    const json = JSON.stringify(data);
    let encrypted = "";
    for (let i = 0; i < json.length; i++) {
        encrypted += String.fromCharCode(json.charCodeAt(i) ^ key);
    }
    return btoa(encrypted);
};

Luma.GenerateWord = function(config) {
    return $.ajax({
        url: "LumaFramework/LumaGenerateWord.php",
        type: "POST",
        dataType: "json",
        contentType: "application/json",
        data: JSON.stringify({ config: config }),
    }).always(() => {
        Swal.close(); // Close the loading modal whether success or failure
    });
}

Luma.Mailer = function(config) {
    Swal.fire({
        title: 'Sending Email...',
        text: 'Please wait while your email is being sent.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    const manilaTime = new Date().toLocaleString("en-US", { timeZone: "Asia/Manila" });
    const manilaDate = new Date(manilaTime);
    const key = (manilaDate.getHours() * 60 + manilaDate.getMinutes()) % 256;
    const encPayload = Luma.EncryptData(config, key);

    return $.ajax({
        url: "LumaFramework/LumaMailer.php",
        type: "POST",
        dataType: "json",
        contentType: "application/json",
        data: JSON.stringify({ config: encPayload })
    }).always(() => {
        Swal.close(); // Closes the loading modal whether success or failure
    });
}
