export default class CheckboxFormController {
  constructor() {
    this.checkboxForm = document.getElementById('checkbox-form');
    this.statusCheckbox = document.getElementById('status-checkbox');
    this.statusCheckbox.addEventListener('change', this.handleSubmit.bind(this));
  }
  
  handleSubmit() {
    this.checkboxForm.submit();
  }
}