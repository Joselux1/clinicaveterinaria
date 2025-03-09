"use strict";

var KTContactApply = function() {
    var submitButton, validator, form, selectedAddress;

    return {
        init: function() {
            form = document.querySelector("#kt_contact_form");
            submitButton = document.getElementById("kt_contact_submit_button");

            if (!form) {
                console.error("Formulario #kt_contact_form no encontrado.");
                return;
            }
            if (!submitButton) {
                console.error("Botón #kt_contact_submit_button no encontrado.");
                return;
            }

            // FormValidation
            validator = FormValidation.formValidation(form, {
                fields: {
                    name: {
                        validators: {
                            notEmpty: {
                                message: "Name is required"
                            }
                        }
                    },
                    email: {
                        validators: {
                            notEmpty: {
                                message: "Email address is required"
                            },
                            emailAddress: {
                                message: "The value is not a valid email address"
                            }
                        }
                    },
                    message: {
                        validators: {
                            notEmpty: {
                                message: "Message is required"
                            }
                        }
                    }
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: ".fv-row",
                        eleInvalidClass: "",
                        eleValidClass: ""
                    })
                }
            });

            // Revalidar campo "position"
            var positionField = form.querySelector('[name="position"]');
            if (positionField) {
                $(positionField).on("change", function() {
                    validator.revalidateField("position");
                });
            }

            // Manejar el submit
            submitButton.addEventListener("click", function(e) {
                e.preventDefault();
                validator.validate().then(function(status) {
                    if (status === "Valid") {
                        submitButton.setAttribute("data-kt-indicator", "on");
                        submitButton.disabled = true;

                        setTimeout(function() {
                            submitButton.removeAttribute("data-kt-indicator");
                            submitButton.disabled = false;

                            Swal.fire({
                                text: "Form has been successfully submitted!",
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });
                        }, 2000);
                    } else {
                        Swal.fire({
                            text: "Please check the form and try again.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        }).then(function() {
                            KTUtil.scrollTop();
                        });
                    }
                });
            });

            // Mapa Leaflet
            if (typeof L !== "undefined") {
                var map = L.map("kt_contact_map", {
                    center: [40.725, -73.985],
                    zoom: 30
                });

                L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                    attribution: '&copy; <a href="https://osm.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                var markerGroup = L.layerGroup().addTo(map);
                var customIcon = L.divIcon({
                    html: '<span class="svg-icon svg-icon-primary shadow svg-icon-3x">📍</span>',
                    iconSize: [32, 32],
                    className: "leaflet-marker"
                });

                L.marker([40.724716, -73.984789], { icon: customIcon }).addTo(markerGroup)
                    .bindPopup("430 E 6th St, New York, 10009.")
                    .
