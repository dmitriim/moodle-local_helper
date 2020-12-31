/**
 * Codebase for the grade item modal form.
 *
 * This code is based off the standard boilerplate for an AMD module.
 * See https://docs.moodle.org/dev/MForm_Modal.
 *
 * @module    local_helper/gradeitemform
 * @package   local_helper
 * @author    Dmitrii Metelkin <dmitriim@catalyst-au.net>
 * @copyright Catalyst IT
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(['jquery', 'core/str', 'core/modal_factory', 'core/modal_events', 'core/fragment', 'core/ajax', 'core/yui'],
    function($, Str, ModalFactory, ModalEvents, Fragment, Ajax, Y) {

        /**
         * Constructor
         *
         * @param {String} selector used to find triggers for the new item modal.
         * @param {int} contextid
         *
         * Each call to init gets it's own instance of this class.
         */
        var gradeItemForm = function(selector, contextid) {
            this.contextid = contextid;
            this.init(selector);
        };

        gradeItemForm.prototype.modal = null;
        gradeItemForm.prototype.contextid = -1;
        gradeItemForm.prototype.id = 0;
        gradeItemForm.prototype.courseid = 0;

        /**
         * Initialise the class.
         *
         * @param {String} selector used to find triggers for the new item modal.
         * @private
         * @return {Promise}
         */
        gradeItemForm.prototype.init = function(selector) {
            // Code is based off this example .... https://docs.moodle.org/dev/MForm_Modal.
            var triggers = $(selector);

            this.buttonId = 'ressubmit_item';
            this.finalexam = false;
            if ( -1 != selector.search('final') ) {
                // This is the form for a "final exam" item.
                this.finalexam = true;
                this.buttonId = 'submit_finalexam';
            }

            triggers.on('click', function(e) {
                var triggerElement = $(e.currentTarget);
                return ModalFactory.create({
                    type: ModalFactory.types.SAVE_CANCEL,
                    title: '',
                    body: '',
                }, triggers).then(function(modal) {
                    // Keep a reference to the modal.
                    this.modal = modal;
                    this.modal.setRemoveOnClose(true);

                    this.id = triggerElement.attr('data-id');
                    this.courseid = triggerElement.attr('data-courseid');
                    this.action = triggerElement.attr('data-action');

                    this.modal.setBody(this.getBody());
                    var title = 'Unknown Item';  // This should never be shown.

                    switch (this.action) {
                        case 'createitemmodal':
                            title = 'On click example 1';
                            break;
                        case 'createfinalmodal':
                            title = 'On click example 2';
                            break;
                    }
                    this.modal.setTitle(title);

                    // Forms are big, we want a big modal.
                    this.modal.setLarge();

                    // Hide the submit buttons from the moodle form every time it is opened...
                    // the modal already has save/cancel buttons.
                    this.modal.getRoot().on(ModalEvents.shown, function() {
                        this.modal.getRoot().append('<style>[data-fieldtype=submit] { display: none ! important; }</style>');
                    }.bind(this));

                    // We catch the modal save event, and use it to submit the form inside the modal.
                    // Triggering a form submission will give JS validation scripts a chance to check for errors.
                    this.modal.getRoot().on(ModalEvents.save, this.submitForm.bind(this));
                    // We also catch the form submit event and use it to submit the form with ajax.
                    this.modal.getRoot().on('submit', 'form', this.submitFormAjax.bind(this));

                    // EDAMOO-4931: Prevent leave site pop-up when we cancel the form changes.
                    this.modal.getRoot().on(ModalEvents.cancel, this.resetFormDirtyState.bind(this));
                    // EDAMOO-4931: Prevent leave site pop-up when clicking the close button.
                    this.modal.getRoot().on('click', '.close', this.resetFormDirtyState.bind(this));
                    // EDAMOO-5349: Add custom id for the save button.
                    this.modal.getRoot().find('[data-action="save"]').attr('id', this.buttonId);

                    return this.modal;
                }.bind(this));
            }.bind(this));
        };

        /**
         * @method getBody
         * @private
         * @return {Promise}
         */
        gradeItemForm.prototype.getBody = function(formdata) {
            if (typeof formdata === "undefined") {
                formdata = {'id': this.id, 'courseid': this.courseid, 'finalexam': this.finalexam};
            }
            // Get the content of the modal.
            var params = {jsonformdata: JSON.stringify(formdata)};
            return Fragment.loadFragment('local_helper', 'item_form', this.contextid, params);
        };

        /**
         * @method handleFormSubmissionResponse
         * @private
         * @return {Promise}
         */
        gradeItemForm.prototype.handleFormSubmissionResponse = function() {
            this.modal.hide();
            // We could trigger an event instead.
            // Yuk.
            this.resetFormDirtyState();
        };

        /**
         * @method handleFormSubmissionFailure
         * @private
         * @return {Promise}
         */
        gradeItemForm.prototype.handleFormSubmissionFailure = function(data) {
            // Oh noes! Epic fail :(
            // Ah wait - this is normal. We need to re-display the form with errors!
            // EDAMOO-5349: Prevent multiple items created on repeat mashing of submit button.
            this.toggleSubmitButton();
            this.modal.setBody(this.getBody(data));
        };

        /**
         * Private method
         *
         * @method submitFormAjax
         * @private
         * @param {Event} e Form submission event.
         */
        gradeItemForm.prototype.submitFormAjax = function(e) {
            // We don't want to do a real form submission.
            e.preventDefault();

            // EDAMOO-5349: Prevent multiple items created on repeat mashing of submit button.
            this.toggleSubmitButton();

            // Convert all the form elements values to a serialised string.
            var formData = this.modal.getRoot().find('form').serialize();

            // EDAMOO-3741: Dirty dirty hack because the form element
            // was disabled so we add it here.
            if (this.finalexam) {
                formData += '&hidden=1';
            }

            // Now we can continue...
            Ajax.call([{
                methodname: 'dummy_method',
                args: {contextid: this.contextid, jsonformdata: JSON.stringify(formData)},
                done: this.handleFormSubmissionResponse.bind(this, formData),
                fail: this.handleFormSubmissionFailure.bind(this, formData)
            }]);
        };

        /**
         * This triggers a form submission, so that any mform elements can do final tricks before the form submission is processed.
         *
         * @method submitForm
         * @param {Event} e Form submission event.
         * @private
         */
        gradeItemForm.prototype.submitForm = function(e) {
            e.preventDefault();
            this.modal.getRoot().find('form').submit();
        };

        /**
         * Helper method to reset form dirty state.
         *
         * @method  resetFormDirtyState
         * @return void
         */
        gradeItemForm.prototype.resetFormDirtyState = function () {
            Y.use('moodle-core-formchangechecker', function() {
                M.core_formchangechecker.reset_form_dirty_state();
            });
            this.modal.destroy();
        };

        /**
         * Disable or enable submit button.
         */
        gradeItemForm.prototype.toggleSubmitButton = function () {
            var button = document.getElementById(this.modal.getRoot().find('[data-action="save"]').attr('id'));

            if (button.disabled) {
                button.disabled = false;
            } else {
                button.disabled = true;
            }
        };

        return {
            init: function(selector, contextid) {
                return new gradeItemForm(selector, contextid);
            }
        };
    });
