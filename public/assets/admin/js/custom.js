var clockTimer = false;
var clockServerUpdate = true;
var clockTemp = true;
var currentTime = "";

$("#common-modal").on("hidden.bs.modal", function () {
    $("#common-modal .modal-body").html("");
});

// For Password and Confirm Password
$(document).ready(function () {
    $("body").on('click', '.toggle-password', function () {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $("#password");
        if (input.attr("type") === "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
        table.ajax.reload(null, false);
    });
    $("body").on('click', '.confirm-password', function () {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $("#password_confirmation");
        if (input.attr("type") === "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
        table.ajax.reload(null, false);
    });
})

$(document).on("click", ".modal-link", function (e) {
    e.preventDefault();
    var URL = $(this).attr("href");
    var title = $(this).data("title");
    console.log('Modal Link', URL, title);

    $("#common-modal .modal-title").text(title);
    $.ajax({
        url: URL,
        cache: false,
        beforeSend: function () {
            $("#lds-roller").show();
        },
        success: function (res) {
            $("#common-modal").modal("show");
            $(".modal-body").html(res);
            $("#lds-roller").hide();
        },
        error: function (request, status, error) {
            if (request.status == "500") {
                toastrerror(error);
                $("#lds-roller").hide();
            }
        },
    });
});

$(document).on("keydown", ".datetimepicker-input", function (e) {
    e.preventDefault();
});

$(document).on("keypress", ".numericonly", function (e) {
    e = e ? e : window.event;
    var charCode = e.which ? e.which : e.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        e.preventDefault();
    } else {
        return true;
    }
});

$(document).on("keypress", ".numeric-dot-only", function (e) {
    e = e ? e : window.event;
    var charCode = e.which ? e.which : e.keyCode;
    if (charCode == 46) {
        return true;
    } else if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        e.preventDefault();
    } else {
        return true;
    }
});

function ucwords(str) {
    // Split the string into words based on spaces
    var words = str.split(" ");

    // Capitalize the first letter of each word and join them back together
    for (var i = 0; i < words.length; i++) {
        words[i] = words[i].charAt(0).toUpperCase() + words[i].slice(1);
    }

    // Join the words back into a single string
    return words.join(" ");
}

function makeEncrypt(formData) {
    const plainObject = {};
    const newFormData = new FormData();

    // Convert formData to a plain object while excluding files
    formData.forEach((value, key) => {
        if (value instanceof File) {
            newFormData.append(key, value); // Directly append files without encryption
        } else {
            const isArrayKey = key.endsWith('[]');
            const cleanKey = isArrayKey ? key.replace(/\[\]$/, '') : key; // Remove [] from key

            if (plainObject[cleanKey]) {
                // If the key already exists, append the new value
                plainObject[cleanKey].push(value);
            } else {
                // Initialize as an array if the key ends with [], otherwise store as a single value
                plainObject[cleanKey] = isArrayKey ? [value] : value;
            }
        }
    });

    // Encrypt the plain object
    const jsonString = JSON.stringify(plainObject);
    const key = CryptoJS.enc.Utf8.parse("WmCywsRVZrbylND2");
    const iv = CryptoJS.enc.Utf8.parse("WmCywsRVZrbylND2");
    const encrypted = CryptoJS.AES.encrypt(jsonString, key, {
        iv: iv,
    }).toString();
    
    // Append the encrypted data to the new FormData object
    newFormData.append("data", encrypted);

    return newFormData;
}


async function DemoMethod(url, method, formData = "", formElement = "") {
    $("#lds-roller").show();

    try {
        const response = await $.ajax({
            type: method,
            url: url,
            data: formData !== "" ? makeEncrypt(formData) : "",
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            }
        });

        $(".errors").text("");

        if (response.success) {
            setTimeout(function() {
                window.location.reload(); 
            }, 100);
        }

        if (response.error) {
            if ((!response.errors || Object.keys(response.errors).length === 0) && response.msg) {
                toastrerror(response.msg);
            } else {
                for (const [key, value] of Object.entries(response.errors)) {
                    if (formElement !== "") {
                        formElement.find(`#${key}error`).text(value);
                    } else {
                        $(`form[action='${url}']`).find(`#${key}error`).text(value);
                    }
                }
            }

            if (response.sweeterror) {
                let errorHTML = "<ul>";
                for (const [key, value] of Object.entries(response.errors)) {
                    errorHTML += `<li><b>${ucwords(key.replace("_", " "))} : </b>${value}</li>`;
                }
                errorHTML += "</ul>";

                Swal.fire({
                    title: "Error",
                    html: errorHTML,
                    icon: "error",
                });
            }
        }

       

        return response;
    } catch (error) {
        if (error.status === 500) {
            toastrerror("Internal Server Error");
        } else {
            toastrerror(error.responseJSON.message);
        }
        return { success: false };
    } finally {
        $("#lds-roller").hide();
    }
}

// when model open like form open then they used this function
async function FormMethod(url, method, formData = "", formElement = "") {

    $("#lds-roller").show();
    const response = await $.ajax({
        type: method,
        url: url,
        data: formData != "" ? makeEncrypt(formData) : "",
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (data) {
            $(".errors").text("");
            if (data.error) {
                if (data.errors.length == "0" && data.msg) {
                    // toastrerror(data.msg);
                } else {
                    for (const [key, value] of Object.entries(data.errors)) {
                        if (formElement != "") {
                            formElement.find("#" + key + "error").text(value);
                        } else {
                            $("form[action='" + url + "']")
                                .find("#" + key + "error")
                                .text(value);
                        }
                        $("#" + key + "error").text(value);
                        // console.log("123", $("#" + key + "error").text(value));
                    }

                    if (data.sweeterror) {
                        var errorHTML = "<ul>";
                        for (const [key, value] of Object.entries(
                            data.errors
                        )) {
                            errorHTML += `<li><b>${ucwords(
                                key.replace("_", " ")
                            )} : </b>${value}</li>`;
                        }
                        errorHTML += "</ul>";

                        Swal.fire({
                            title: "Error",
                            html: errorHTML,
                            icon: "error",
                        });
                    }
                }
            } else if (data.success && data.route) {
                console.log(data.socket_alert && socket);
                if (data.socket_alert && socket) {
                    socket.emit(data.socket_trigger, data.socket_notification);
                }
                window.location.href = data.route;
            }else{
                toastrerror(data.msg);
            }
        },
        error: function (request, status, error) {
            if (request.status == "500" || request.status == "405" || error == true) {
                toastrerror(error);
                $("#lds-roller").hide();
            }
        },
    });
    $("#lds-roller").hide();
    return response;
}

// when model open like form open then they used this function
async function ajaxDynamicMethod(url, method, formData = "", formElement = "") {

    $("#lds-roller").show();
    const response = await $.ajax({
        type: method,
        url: url,
        data: formData != "" ? makeEncrypt(formData) : "",
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (data) {
            $(".errors").text("");
            if (data.error) {
                if (data.errors.length == "0" && data.msg) {
                    toastrerror(data.msg);
                } else {
                    for (const [key, value] of Object.entries(data.errors)) {
                        if (formElement != "") {
                            formElement.find("#" + key + "error").text(value);
                        } else {
                            $("form[action='" + url + "']")
                                .find("#" + key + "error")
                                .text(value);
                        }
                        $("#" + key + "error").text(value);
                        // console.log("123", $("#" + key + "error").text(value));
                    }

                    if (data.sweeterror) {
                        var errorHTML = "<ul>";
                        for (const [key, value] of Object.entries(
                            data.errors
                        )) {
                            errorHTML += `<li><b>${ucwords(
                                key.replace("_", " ")
                            )} : </b>${value}</li>`;
                        }
                        errorHTML += "</ul>";

                        Swal.fire({
                            title: "Error",
                            html: errorHTML,
                            icon: "error",
                        });
                    }
                }
            } else if (data.success && data.route) {
                console.log(data.socket_alert && socket);
                if (data.socket_alert && socket) {
                    socket.emit(data.socket_trigger, data.socket_notification);
                }
                window.location.href = data.route;
            }
        },
        error: function (request, status, error) {
            if (request.status == "500" || request.status == "405") {
                toastrerror(error);
                $("#lds-roller").hide();
            }
        },
    });
    $("#lds-roller").hide();
    return response;
}

$(document).on("change", ".custom-file-input", function (e) {
    if ($(this).val() != "" && $(this).val() != null) {
        $(this).parent().find(".custom-file-label").text($(this).val());
        var thisRef = this;
        if ($(this).hasClass("preview-required")) {
            $(thisRef).parent().parent().find(".prview-section").remove();
            var file = e.target.files[0];
            var reader = new FileReader();
            reader.onload = function (e) {
                $(thisRef)
                    .parent()
                    .parent()
                    .append(
                        '<div class="mt-3 prview-section"><img class="w-100" src="' +
                        e.target.result +
                        '"></div>'
                    );
            };
            reader.readAsDataURL(file);
        }
    } else {
        $(this)
            .parent()
            .find(".custom-file-label")
            .text(
                $(this).parent().find(".custom-file-label").data("label")
                    ? $(this).parent().find(".custom-file-label").data("label")
                    : "Choose Image"
            );
        $(this).parent().parent().find(".prview-section").remove();
    }
});

function checkMultipleDetails(form) {
    var frequencyMap = {};
    $.each(form, function (index, item) {
        var name = item.name;
        frequencyMap[name] = (frequencyMap[name] || 0) + 1;
    });

    // Extract names with frequency greater than 1
    var repeatedNames = [];
    for (var name in frequencyMap) {
        if (frequencyMap.hasOwnProperty(name) && frequencyMap[name] > 1) {
            repeatedNames.push(name);
        }
    }

    return repeatedNames;
}

function objectToFormData(obj, form, namespace) {
    let fd = form || new FormData();
    let formKey;

    for (let property in obj) {
        if (obj.hasOwnProperty(property)) {
            if (namespace) {
                formKey = namespace + "[" + property + "]";
            } else {
                formKey = property;
            }

            // If the property is an object, but not a File, use recursion.
            if (
                typeof obj[property] === "object" &&
                !(obj[property] instanceof File)
            ) {
                objectToFormData(obj[property], fd, formKey);
            } else {
                // If it's a string or a File object, append it.
                fd.append(formKey, obj[property]);
            }
        }
    }
    return fd;
}

function generateFormData(param) {
    var dataArr = $(param).serializeArray();
    var mutipleValue = checkMultipleDetails(dataArr);
    var formData = new FormData();
    $.each(dataArr, function (i, field) {
        if ($.inArray(field.name, mutipleValue) !== -1) {
        } else {
            formData.append(field.name, field.value);
        }
    });
    $.each(mutipleValue, function (index, value) {
        $.each(dataArr, function (i, field) {
            if (field.name == value) {
                formData.append(
                    field.name.replace("[]", "") + "[]",
                    field.value
                );
            }
        });
    });
    $.each(
        $(param).find('input[type="file"]:not([multiple])'),
        function (i, field) {
            formData.append(
                field.name,
                $(field)[0].files[0] != undefined ? $(field)[0].files[0] : ""
            );
        }
    );
    $.each($(param).find('input[type="file"][multiple]'), function (i, field) {
        var fileInput = $(field)[0];
        var files = fileInput.files;
        for (var i = 0; i < files.length; i++) {
            formData.append(field.name.replace("[]", "") + "[]", files[i]);
        }
        // formData.append(field.name + "[]", (($(field)[0].files[0]) != undefined) ? $(field)[0].files[0] : "");
    });

    return formData;
}

$(document).on("click", ".sidebar-link", async function () {
    var url = $(this).data("url");
    if ($("#kt_side_panel").hasClass("offcanvas-on")) {
        closeSettingSidebar();
    } else {
        var response = await ajaxDynamicMethod(url, "GET");
        $("#kt_side_panel .tab-content").html(response.data.html);
        $("#kt_side_panel").addClass("offcanvas-on");
    }
});

function closeSettingSidebar(alert = true) {
    if (alert) {
        Swal.fire({
            title: "Are you sure?",
            text: "You will not be able to recover this action!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, proceed!",
        }).then((result) => {
            if (result.isConfirmed) {
                $("#kt_side_panel").removeClass("offcanvas-on");
            }
        });
    } else {
        $("#kt_side_panel").removeClass("offcanvas-on");
    }
}

$(document).on("click", "#kt_side_panel_close", function (e) {
    e.preventDefault();
    closeSettingSidebar();
});

$(document).on("blur", ".change-layout-text", async function () {
    var formData = new FormData();
    formData.append("column", $(this).data("column"));
    formData.append("layout_id", $(this).data("id"));
    formData.append("text", $(this).text().trim());
    var data = await ajaxDynamicMethod($(this).data("url"), "POST", formData);
    if (data.success) {
        socket.emit("updatesection", {
            page: "home",
            layout_id: data.socket_trigger,
        });
        toastrsuccess(data.msg);
    }
});

function layout1Assign() {
    $(".listSlider1").show();
    $(".listSlider1").slick({
        dots: true,
        arrows: false,
        rows: 2,
        autoplay: true,
        infinite: true,
        slidesToShow: 2,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 767,
                settings: {
                    autoplay: true,
                    infinite: true,
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    rows: 1,
                    centerMode: true,
                },
            },
            {
                breakpoint: 481,
                settings: {
                    autoplay: true,
                    infinite: true,
                    //rows: 1,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    rows: 1,
                    centerMode: true,
                },
            },
        ],
    });
}

function layout2Assign() {
    $(".headlinesSlider").show();
    $(".headlinesSlider").slick({
        dots: false,
        arrows: true,
        rows: 4,
        autoplay: false,
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        adaptiveHeight: true,
        responsive: [
            {
                breakpoint: 767,
                settings: {
                    rows: 3,
                },
            },
            {
                breakpoint: 571,
                settings: {
                    rows: 2,
                },
            },
        ],
    });
    $(".popularSlider").show();
    $(".popularSlider").slick({
        dots: false,
        arrows: true,
        rows: 4,
        autoplay: false,
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        adaptiveHeight: true,
        responsive: [
            {
                breakpoint: 767,
                settings: {
                    rows: 3,
                },
            },
            {
                breakpoint: 571,
                settings: {
                    rows: 2,
                },
            },
        ],
    });
}

function layout3Assign() {
    // alert(132);
}

function layout4Assign() {
    $(".listSlider4").show();
    $(".listSlider4").slick({
        dots: true,
        arrows: false,
        rows: 2,
        autoplay: true,
        infinite: true,
        slidesToShow: 2,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 767,
                settings: {
                    autoplay: true,
                    infinite: true,
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    rows: 1,
                    centerMode: true,
                },
            },
            {
                breakpoint: 481,
                settings: {
                    autoplay: true,
                    infinite: true,
                    //rows: 1,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    rows: 1,
                    centerMode: true,
                },
            },
        ],
    });
}

// you can add this any tag onclick="OpenExcel() after open excel"
function OpenExcel() {
    const fileURL = 'https://docs.google.com/spreadsheets/d/1n35OEdSLmpTys5jI2zS0N8WI3Dm0ILv9NVcuHTqGwLs/edit?gid=0#gid=0';
    window.open(fileURL, '_blank');
}

//You can any tag add this onclick="selectText() after background color change yellow and white"
function selectText() {
    var button = document.getElementById('selectButton');

    if (button.style.backgroundColor === 'yellow') {
        button.style.backgroundColor = 'white';
    } else {
        button.style.backgroundColor = 'yellow';
    }
}

function pageRedirect(module, slug) {
    if (module == "Post" || module == "User") {
        window.location.href =
            "http://127.0.0.1:8000/admin/" +
            module.toLowerCase() +
            "/" +
            slug +
            "/edit";
    }
    return false;
}

window.onpageshow = function (event) {
    if (event.persisted) {
        location.reload();
    }
};

// When page load Image Show only you can add this on you code
/* <img loading="lazy" data-src="{{ asset('assets/admin/image/coding.png') }}" alt="Lazy Load Example"
                    class="lazy-load-image" /> */
document.addEventListener("DOMContentLoaded", function () {
    let lazyImages = document.querySelectorAll('img[loading="lazy"]');

    const lazyLoad = (target) => {
        const io = new IntersectionObserver((entries, observer) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute("data-src");

                    img.style.visibility = "hidden";

                    img.onload = function () {
                        img.style.visibility = "visible";
                    };

                    observer.disconnect();
                }
            });
        });

        io.observe(target);
    };

    lazyImages.forEach(lazyLoad);
});

// document.addEventListener("DOMContentLoaded", function () {
//     const otpInputs = document.querySelectorAll(".otp-input");

//     otpInputs.forEach((input) => {
//         input.addEventListener("paste", handlePaste);
//         input.addEventListener("keyup", handleKey);
//         input.addEventListener("input", restrictToNumbers);
//     });
// });

$(document).on("paste", ".otp-input", function (event) {
    const clipboardData =
        event.originalEvent.clipboardData || window.originalEvent.clipboardData;
    const pastedData = clipboardData.getData("Text");
    let otp = pastedData.trim().replace(/[^\d]/g, "");
    for (let i = 1; i <= 6; i++) {
        $(`#oc${i}`).val("");
    }
    for (let i = 1; i <= otp.length && i <= 6; i++) {
        $(`#oc${i}`).val(otp[i - 1]);
    }
    $(`#oc${otp.length}`).focus();
    // if (otp.length < 6) {
    // }
});

$(document).on("keyup", ".otp-input", function (event) {
    const input = event.target;
    const keyCode = event.keyCode || event.which;

    if (input.value.length >= 1) {
        let nextInput = input.nextElementSibling;
        if (nextInput && nextInput.classList.contains("otp-input")) {
            nextInput.focus();
        }
    }

    // Backspace key
    if (keyCode === 8 && !input.value) {
        let prevInput = input.previousElementSibling;
        if (prevInput && prevInput.classList.contains("otp-input")) {
            prevInput.focus();
        }
    }
});

$(document).on("input", ".otp-input", function (event) {
    const input = event.target;
    input.value = input.value.replace(/[^\d]/g, "");
});

async function updateServerClock(id) {
    var data = await ajaxDynamicMethod(
        $(id).attr("action"),
        $(id).attr("method"),
        generateFormData(id)
    );
    if (data.success) {
        currentTime = data.data.total;
        $(".clock-in-out .toggle-group .toggle-on").text(data.data.total);
        $(".clock-in-out .toggle-group .toggle-off").text(
            formatTimeToViewFormat(data.data.total)
        );
        $(".clock-in-out #clockToggle").attr("data-on", data.data.total);
        $(".clock-in-out #clockToggle").attr(
            "data-off",
            formatTimeToViewFormat(data.data.total)
        );
        toastrsuccess(data.msg);
        if ($("#clockToggle").prop("checked")) {
            if (!clockTemp) {
                clockTemp = false;
                $("#clockToggle").bootstrapToggle("off");
                $("#custom-dropdown").hide();
                $("#kt_header").removeClass("bg-clock-disabled");
                $("#clock-status-form")[0].reset();
                clockServerUpdate = true;
            }
            clockTimer = true;
        } else {
            clockTimer = false;
        }
    }
}

$(document).on("click", ".clock-close", function (e) {
    e.preventDefault();
    $("#custom-dropdown").hide();
    $("#kt_header").removeClass("bg-clock-disabled");
    clockTemp = true;
});

$(document).on("submit", "#clock-status-form", function (e) {
    e.preventDefault();
    updateServerClock("#clock-status-form");
});

$(document).on("change", "#clockToggle", async function () {
    if (!$(this).prop("checked")) {
        clockServerUpdate = false;
        if (clockTemp) {
            $(this).bootstrapToggle("on");
            clockTemp = false;
        } else {
            clockTemp = true;
        }
        $("#custom-dropdown").show();
        $("#kt_header").addClass("bg-clock-disabled");
    } else if (clockServerUpdate) {
        updateServerClock("#clock-in-form");
    }
});

// Convert Value to hour and minite
function formatMinutesToTime(minutes) {
    var hours = Math.floor(minutes / 60);
    var mins = minutes % 60;
    return hours + "hr " + mins + "min";
}

function formatTimeToViewFormat(timeString) {
    var timeArray = timeString.split(":");
    var hours = parseInt(timeArray[0]);
    var minutes = parseInt(timeArray[1]);

    var formattedTime = "";
    if (hours > 0) {
        formattedTime += hours + "hr ";
    } else {
        formattedTime += "0hr ";
    }
    if (minutes > 0) {
        formattedTime += minutes + "mins";
    } else if (minutes == 1) {
        formattedTime += minutes + "min";
    } else {
        formattedTime += "0min";
    }

    return formattedTime;
}

$(document).ready(function () {
    $("#clock_off_status").select2({
        dropdownCssClass: "increase-z-index",
    });

    function incrementTime() {
        var timeArray = currentTime.split(":");
        var hours = parseInt(timeArray[0], 10);
        var minutes = parseInt(timeArray[1], 10);
        var seconds = parseInt(timeArray[2], 10);

        // Increment by one second
        seconds++;

        // Handle overflow to minutes and hours
        if (seconds >= 60) {
            seconds = 0;
            minutes++;
        }

        if (minutes >= 60) {
            minutes = 0;
            hours++;
        }

        // Format the time
        var formattedTime =
            padZero(hours) + ":" + padZero(minutes) + ":" + padZero(seconds);

        // Update the display
        $(".clock-in-out .toggle-group .toggle-on").attr(
            "title",
            formattedTime
        );
        $(".clock-in-out .toggle-group .toggle-on").text(
            formatTimeToViewFormat(formattedTime)
        );

        // Update the current time
        currentTime = formattedTime;
    }

    function padZero(value) {
        return value < 10 ? "0" + value : value;
    }

    if ($("#clockToggle").is(":checked")) {
        clockTimer = true;
        currentTime = $("#clockToggle").data("time");
    }

    setInterval(function () {
        if (clockTimer) {
            incrementTime();
        }
    }, 1000);
});


function reloadLeads() {
    fetch('{{ route("admin.lead.index") }}', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        renderKanban(data.leads, data.leadnews); // re-render with new data
    })
    .catch(error => {
        console.error('Failed to reload leads:', error);
        toastrerror('Could not reload lead data.');
    });
}

function FormReload(data) {
    if (data && data.msg) {
        alert(data.route)
        window.location.href = data.route;
    }
}