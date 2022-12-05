<div class="form-inline">
                <div class="form-group">
                    <button class="popovers btn btn-link dropdown-toggle button-on-hover" data-container="body" data-placement="bottom" data-trigger="hover" data-content="Update My Data" type="button" onclick="actionBarClicked('update_data')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 svg-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" data-container="body" data-placement="bottom" data-trigger="hover" data-content="Update My Data">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                    </button>
                    <button class="popovers btn btn-link dropdown-toggle button-on-hover" data-container="body" data-placement="bottom" data-trigger="hover" data-content="Mail Vendor" type="button" onclick="actionBarClicked('vendor')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 svg-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" data-container="body" data-placement="bottom" data-trigger="hover" data-content="Mail Vendor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </button>
                    <button class="popovers btn btn-link dropdown-toggle button-on-hover" data-container="body" data-placement="bottom" data-trigger="hover" data-content="View Invoice" type="button" onclick="actionBarClicked('view_invoice')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 svg-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" data-container="body" data-placement="bottom" data-trigger="hover" data-content="View Invoice">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </button>
                    <div class="btn-group">
                        <button class="btn btn-link dropdown-toggle" type="button" data-toggle="dropdown">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 svg-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <input type="hidden" id="recon_status" name="recon_status_value" value="" />
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="#" onclick="setReconStatus('Matched')">Matched</a></span>
                            </li>
                            <li>
                                <a href="#" onclick="setReconStatus('Reconciled')">Reconciled</a></span>
                            </li>
                            <li>
                                <a href="#" onclick="setReconStatus('Pending')">Pending</a></span>
                            </li>
                            <li>
                                <a href="#" onclick="setReconStatus('Missing in my data')">Missing in my data</a></span>
                            </li>
                            <li>
                                <a href="#" onclick="setReconStatus('Missing in vendor GST filing')">Missing in vendor GST filing</a></span>
                            </li>
                            <li>
                                <a href="#" onclick="setReconStatus('Mismatch in values')">Mismatch in values</a></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>