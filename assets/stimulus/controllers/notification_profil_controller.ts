// @ts-ignore
import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  static targets = ["all", "issueCreated", "issueUpdated", "commentCreated", "commentUpdated", "commentTagOnly"];

  declare readonly allTarget: HTMLInputElement;
  declare readonly issueCreatedTarget: HTMLInputElement;
  declare readonly issueUpdatedTarget: HTMLInputElement;
  declare readonly commentCreatedTarget: HTMLInputElement;
  declare readonly commentUpdatedTarget: HTMLInputElement;
  declare readonly commentTagOnlyTarget: HTMLInputElement;

  connect(): void {
    console.log("📝 NotificationProfil controller connected.");
  }

  toggleAll(event: Event): void {
    const isChecked = this.allTarget.checked;

    if (!isChecked) {
      this.issueCreatedTarget.checked = false;
      this.issueUpdatedTarget.checked = false;
      this.commentCreatedTarget.checked = false;
      this.commentUpdatedTarget.checked = false;
      this.commentTagOnlyTarget.checked = false;
    } else {
      this.issueCreatedTarget.checked = true;
      this.issueUpdatedTarget.checked = true;
      this.commentCreatedTarget.checked = true;
      this.commentUpdatedTarget.checked = true;
      this.commentTagOnlyTarget.checked = false;
    }
  }

  commentTagOnly(event: Event): void {
    const isChecked = this.commentTagOnlyTarget.checked;

    if (isChecked) {
      this.commentCreatedTarget.checked = false;
      this.commentUpdatedTarget.checked = false;
    }
  }

  commentCreatedOrUpdated(event: Event): void {
    this.commentTagOnlyTarget.checked = false;
  }

  notification(event: Event): void {
    const allIsChecked = this.allTarget.checked;

    if (!allIsChecked) {
      this.issueCreatedTarget.checked = false;
      this.issueUpdatedTarget.checked = false;
      this.commentCreatedTarget.checked = false;
      this.commentUpdatedTarget.checked = false;
      this.commentTagOnlyTarget.checked = false;
    }
  }

}

