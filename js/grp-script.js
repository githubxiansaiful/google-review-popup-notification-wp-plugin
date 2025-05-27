jQuery(document).ready(function ($) {
    // Check if in admin context
    if (typeof grp_ajax_object.is_admin !== 'undefined' && grp_ajax_object.is_admin) {
        $('#grp-preview-button').on('click', function () {
            const pageLoadTime = Date.now(); // Track preview start time
            const popupDuration = grp_ajax_object.popup_duration * 1000; // Convert to milliseconds
            let isActive = true;

            $.post(grp_ajax_object.ajax_url, { action: 'grp_get_reviews' }, function (response) {
                if (response.success && response.data.reviews.length) {
                    const reviews = response.data.reviews;
                    let currentIndex = 0;
                    let isHovered = false;
                    let timeoutId;

                    function showNextReview() {
                        // Check if duration has elapsed
                        if (popupDuration > 0 && (Date.now() - pageLoadTime) > popupDuration) {
                            isActive = false;
                            $('#grp-preview-container .grp-main').remove();
                            return;
                        }
                        if (!isActive) return;

                        $('#grp-preview-container .grp-main').remove();

                        let review = reviews[currentIndex];
                        let rating = review.rating || 0;
                        let fullStars = Math.floor(rating);
                        let halfStar = rating % 1 >= 0.5 ? '☆' : '';
                        let emptyStars = 5 - Math.ceil(rating);
                        let stars = '★'.repeat(fullStars) + halfStar + '☆'.repeat(emptyStars);

                        let popup = $(`
                            <div class="grp-main">
                                <a href="${review.author_url}" target="_blank">
                                    <div class="grp-user-info">
                                        <div class="grp-user-photo">
                                            <img src="${review.profile_photo_url || 'https://pbs.twimg.com/media/Gr4ltrsWEAASDtQ?format=jpg&name=240x240'}" alt="${review.author_name}'s photo">
                                        </div>
                                        <div class="grp-user-name">
                                            <p>${review.author_name}</p>
                                            <div class="grp-stars">${stars}</div>
                                        </div>
                                    </div>
                                    <div class="grp-popup-content"><p>${review.text}</p></div>
                                </a>
                            </div>
                        `);

                        popup.css('animation-name', grp_ajax_object.animation_type);

                        $('#grp-preview-container').append(popup);

                        popup.hover(
                            function () {
                                isHovered = true;
                                clearTimeout(timeoutId);
                            },
                            function () {
                                isHovered = false;
                                timeoutId = setTimeout(() => {
                                    if (isActive) {
                                        popup.fadeOut(500, () => {
                                            popup.remove();
                                            currentIndex = (currentIndex + 1) % reviews.length;
                                            setTimeout(showNextReview, grp_ajax_object.popup_delay);
                                        });
                                    }
                                }, grp_ajax_object.hover_pause);
                            }
                        );

                        if (!isHovered) {
                            timeoutId = setTimeout(() => {
                                if (isActive) {
                                    popup.fadeOut(500, () => {
                                        popup.remove();
                                        currentIndex = (currentIndex + 1) % reviews.length;
                                        setTimeout(showNextReview, grp_ajax_object.popup_delay);
                                    });
                                }
                            }, grp_ajax_object.hover_pause);
                        }
                    }

                    showNextReview();
                } else {
                    $('#grp-preview-container').html('<p>No reviews available for preview.</p>');
                }
            }).fail(function (error) {
                $('#grp-preview-container').html('<p>Failed to load preview.</p>');
            });
        });
    } else {
        // Front-end behavior
        const pageLoadTime = Date.now(); // Track page load time
        const popupDuration = grp_ajax_object.popup_duration * 1000; // Convert to milliseconds
        let isActive = true;

        $.post(grp_ajax_object.ajax_url, { action: 'grp_get_reviews' }, function (response) {
            if (response.success && response.data.reviews.length) {
                const reviews = response.data.reviews;
                let currentIndex = 0;
                let isHovered = false;
                let timeoutId;

                function showNextReview() {
                    // Check if duration has elapsed
                    if (popupDuration > 0 && (Date.now() - pageLoadTime) > popupDuration) {
                        isActive = false;
                        $('.grp-main').remove();
                        return;
                    }
                    if (!isActive) return;

                    $('.grp-main').remove();

                    let review = reviews[currentIndex];
                    let rating = review.rating || 0;
                    let fullStars = Math.floor(rating);
                    let halfStar = rating % 1 >= 0.5 ? '☆' : '';
                    let emptyStars = 5 - Math.ceil(rating);
                    let stars = '★'.repeat(fullStars) + halfStar + '☆'.repeat(emptyStars);

                    let popup = $(`
                        <div class="grp-main">
                            <a href="${review.author_url}" target="_blank">
                                <div class="grp-user-info">
                                    <div class="grp-user-photo">
                                        <img src="${review.profile_photo_url || 'https://pbs.twimg.com/media/Gr4ltrsWEAASDtQ?format=jpg&name=240x240'}" alt="${review.author_name}'s photo">
                                    </div>
                                    <div class="grp-user-name">
                                        <p>${review.author_name}</p>
                                        <div class="grp-stars">${stars}</div>
                                    </div>
                                </div>
                                <div class="grp-popup-content"><p>${review.text}</p></div>
                            </a>
                        </div>
                    `);

                    popup.css('animation-name', grp_ajax_object.animation_type);

                    $('body').append(popup);

                    popup.hover(
                        function () {
                            isHovered = true;
                            clearTimeout(timeoutId);
                        },
                        function () {
                            isHovered = false;
                            timeoutId = setTimeout(() => {
                                if (isActive) {
                                    popup.fadeOut(500, () => {
                                        popup.remove();
                                        currentIndex = (currentIndex + 1) % reviews.length;
                                        setTimeout(showNextReview, grp_ajax_object.popup_delay);
                                    });
                                }
                            }, grp_ajax_object.hover_pause);
                        }
                    );

                    if (!isHovered) {
                        timeoutId = setTimeout(() => {
                            if (isActive) {
                                popup.fadeOut(500, () => {
                                    popup.remove();
                                    currentIndex = (currentIndex + 1) % reviews.length;
                                    setTimeout(showNextReview, grp_ajax_object.popup_delay);
                                });
                            }
                        }, grp_ajax_object.hover_pause);
                    }

                    console.log(`Review ${currentIndex + 1}:`, review);
                }

                setTimeout(showNextReview, grp_ajax_object.popup_delay);
            } else {
                console.log('No reviews found or request failed:', response);
            }
        }).fail(function (error) {
            console.log('AJAX request failed:', error);
        });
    }
});