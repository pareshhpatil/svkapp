<style>
    .panel-wrap {
        position: fixed;
        top: 0;
        bottom: 0;
        right: 0;
        left: 70% !important;
        /* width: 80em; */
        transform: translateX(100%);
        transition: .3s ease-out;
        z-index: 100;
    }

    .panel {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: #fff;
        color: #394242;
        overflow-y: scroll;
        overflow-x: hidden;
        padding: 1em;
        box-shadow: 0 5px 15px rgb(0 0 0 / 50%);
        margin-bottom: 0;
    }


    .subscription-info i {
        font-size: 22px !important;
    }

    .cust-head {
        text-align: left !important;
    }

    .subscription-info h3 {
        text-align: center;
        color: #000;
        margin-bottom: 2px !important;
    }

    .subscription-info h2 {
        font-weight: 600;
        margin-bottom: 0 !important;
        margin-top: 5px !important;
        text-align: center;
    }

    .td-head {
        font-size: 19px;
    }

    @media (max-width: 767px) {
        .cust-head {
            text-align: center !important;
        }

        .panel-wrap {
            /* width: 23em; */
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
            position: fixed;
        }
    }

    @media (min-width: 768px) and (max-width: 991px) {
        .panel-wrap {
            /* width: 47em; */
            position: fixed;
            right: 0;
        }
    }

    @media (min-width: 992px) and (max-width: 1199px) {
        .panel-wrap {
            /* width: 47em; */
            position: fixed;
            right: 0;
        }
    }

    .right-value {
        text-align: right;
    }
</style>

<div class="panel-wrap" style="left: 70% !important;" wire:ignore id="panelWrapIdgroup">

    <div class="panel">
        <div class='cnt223'>
            <h3 class="modal-title">Add sub total group
                <a class="close " data-toggle="modal" onclick="closegroup();">
                    <button type="button" class="close" aria-hidden="true"></button></a>

            </h3>
            <hr>
            <div class="form-body">
                <!-- Start profile details -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-md-4">Group Name<span class="required">*
                                </span></label>
                            <div class="col-md-8">
                                <input type="text" maxlength="45" x-model="group_name" class="form-control" placeholder="Enter group name">
                                <input type="hidden" id="group_index">
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <a href="#" onclick="closegroup();" class="btn default">Cancel</a>
                                        <a href="#" @click="saveGroup();" class="btn blue">Save</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <br>
            <!-- <div class="" x-show="group_show">
                <div class="portlet-body form">
                    <h3 class="form-section">Group List</h3>
                    <div class="table-scrollable">
                        <table class="table table-bordered table-hover" id="particular_table1">
                            <thead>
                                <tr>
                                    <th class="td-c">
                                        <b>Group name</b>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(grp, gindex) in groups" :key="gindex">
                                    <tr>
                                        <td class="td-c" x-text="grp">
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> -->

        </div>
    </div>
</div>

<script>
    function setgroup(id) {

        document.getElementById("panelWrapIdgroup").style.boxShadow = "0 0 0 9999px rgba(0,0,0,0.5)";
        document.getElementById("panelWrapIdgroup").style.transform = "translateX(0%)";
        document.getElementById("group_index").value = id;
    }

    function closegroup() {

        document.getElementById("panelWrapIdgroup").style.boxShadow = "";
        document.getElementById("panelWrapIdgroup").style.transform = "";
    }
</script>