<?php

//dynamic function to create the form fields
function buildMetaField( $type = "", $name = "", $label = "", $value = "", $options = "" )
{
    $html = "<div class='custom-meta-field'>";
    if( !empty( $label ) ) {
        $label = esc_html($label) . ':';
        $html .= "<label class='input-label' for='$name'>$label</label>";
    }

    ( !empty( $name ) ) ? $name = esc_html( $name ) : "";

    switch( $type ) {
        case 'input':
            if ($options === 'checkbox') {
                $checked = $value ? 'checked' : '';
                $html .= "
                <div class='meta-box-checkbox'> 
                    <input type='checkbox' name='$name' id='$name' class='meta-box-input' value='1' $checked>
                </div>";
            } elseif (is_array($options) && !empty($options)) {
				$html .= "<div class=meta-box-radio>";
				foreach ($options as $option_value => $option_label) {
                    $checked = checked($value, $option_value, false);
                    $html .= "
                    <div class='meta-radio-select'>
                        <label>
                            <input type='radio' name='$name' id='$name-$option_value' value='$option_value' $checked> $option_label
                        </label>
                    </div>";
                }
				$html .= "</div>";
            } else {
                $html .= "<input type='$options' name='$name' id='$name' class='meta-box-input' value='$value'>";
            }
            break;

        case 'select':
            $html .= "<select name='$name' id='$name' class='meta-box-select'>";

            if( !empty ( $options ) && is_array( $options ) ) {
                $html .= "<option value=''>-- Please select --</option>";
                foreach( $options as $option_value => $label ) {
                    $selected = selected( $value, $option_value, false );
                    $html .= "<option value='$option_value' $selected>$label</option>";
                }
            }

            $html .= "</select>";
            break;

        case 'textarea':
            $html .= "<textarea name='$name' id='$name' class='meta-box-textarea'>$value</textarea>";
            break;

        default:
            break;
    }

    $html .= "</div>";

    echo $html;
}

// Function for saving checkbox data
function save_watertrading_requests_fields( $post_id ) {
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
        return;

	if (!current_user_can('edit_page', $post_id)) {
		return;
	}

    $checkbox_fields = ['can_accept_trucks', 'can_accept_pipelines','can_deliver', 'truck', 'layflats', 'quality_disclosures'];
    
    foreach ($checkbox_fields as $field) {
        $value = isset($_POST[$field]) ? 1 : 0;
        update_post_meta($post_id, $field, $value);
    }
}

add_action( 'save_post', 'save_watertrading_requests_fields' );

// function to build out request form fields
function buildFormField( $id = "", $label = "", $type = 'text', $required = "", $placeholder = "", $acf_key = "", $class = "", $readOnly = '' ) {
	if ($type) {
		switch ($type) {
			case 'text':
				$input = "<input type='text' class='form-control$class' id='$id' name='$id' placeholder='$placeholder' $required $readOnly>";
				break;

			case 'number':
				$input = "<input type='number' class='form-control$class' id='$id' name='$id' placeholder='$placeholder' $required $readOnly>";
				break;

			case 'date':
				$input = "
					<div class='watersharing-row no-margin-bottom'>
						<div class='watersharing-date-col start-dp'>
							<input type='date' class='form-control placeholder-toggle start-dp-wrapper inital$class' id='start_date' name='start_date' $required>
						</div>
						<div class='watersharing-date-sep'>—</div>
						<div class='watersharing-date-col end-dp'>
							<input type='date' class='form-control placeholder-toggle end-dp-wrapper inital$class' id='end_date' name='end_date' $required disabled>
						</div>
					</div>
				";
				break;
			case 'latlong':
				$input = "
					<div class='watersharing-row no-margin-bottom'>
						<div class='watersharing-text-col'>
							<input type='text' class='form-control' placeholder='Latitude' id='latitude' name='latitude' $required>
						</div>
						<div class='watersharing-text-col'>
							<input type='text' class='form-control' placeholder='Longitude' id='longitude' name='longitude' $required>
						</div>
					</div>
				";
				break; 
			
			case 'bid_type':
				$input = "
					<div class='custom-meta-field'>
						<div class=meta-box-radio>
							<div class='meta-radio-select'>
								<input type='radio' name='bid_type' id='bid_type-willing_to_pay' value='willing_to_pay' ''> 
								<label>
									Willing to pay
								</label>	
							</div>
							<div class='meta-radio-select'>
								<input type='radio' name='bid_type' id='bid_type-paid_limit' value='paid_limit' ''> 
								<label>
									Paid at least
								</label>
							</div>
						</div>
					</div>
			";
			break;

			case 'quality_disclosure':
				$input = "
					<div class='watersharing-row no-margin-bottom'>
						<div class='watersharing-text-col'>
							<input type='text' class='form-control' placeholder='Limit' id='{$id}_limit' name='{$id}_radius' $required>
						</div>
						<div class='watersharing-text-col'>
							<input type='text' class='form-control' placeholder='Value' id='{$id}_value' name='{$id}_value' $required>
						</div>
					</div>
				";
				break;

			case 'toggle':
				$input = "
					<div class='watersharing-text-col'>
						<input type = 'checkbox' class='toggle' id = 'toggle' name = 'toggle' onclick = test()>
					</div>
					<script>
						function test(){
							let trucks = document.querySelector('.trucks');

							if(trucks){
								if(trucks.style.display === 'none'){
									trucks.style.display = '';
								}
								else{
									trucks.style.display = 'none';
								}
							}
						}
					</script>
					";
				break;
			
			case 'layflats':
				$input = "
					<div class='watersharing-row no-margin-bottom'>
						<div class='watersharing-text-col'>
							<input type='text' class='form-control' placeholder='Radius' id='layflats_transport_radius' name='layflats_transport_radius' $required>
						</div>
						<div class='watersharing-text-col'>
							<input type='text' class='form-control' placeholder='Bid' id='layflats_transport_bid' name='layflats_transport_bid' $required>
						</div>
						<div class='watersharing-text-col'>
							<input type='text' class='form-control' placeholder='Capacity' id='layflats_transport_capacity' name='layflats_transport_capacity' $required>
						</div>
					</div>
				";
				break; 

			case 'bid_units':
				$input = "
					<select name='bid_units' id='bid_units' class='user-select'>
						<option value='' selected hidden disabled>Manage Selection</option>
						<option value='usd/day'>USD/day</option>
						<option value='usd/bbl.day'>USD/bbl.day</option>
					</select>
				";
				break;

			case 'pads':
				$pads = new WP_Query(
					array(
						'no_found_watersharing-watersharing-rows'			=> false,
						'update_post_meta_cache'	=> false,
						'update_post_term_cache'	=> false,
						'post_type'				=> 'well_pad',
						'post_status'				=> 'publish',
						'posts_per_page'			=> -1,
						'fields'					=> 'ids',
						'meta_key'          		=> 'userid',
						'meta_value' 				=> get_current_user_id()
					)
				);
				$options = "<option value='newPad'>Create A New Pad</option>";
				if( !empty( $pads->posts ) ) {
					foreach( $pads->posts as $pad ) {
						$title = get_the_title( $pad );
						$lat = get_post_meta( $pad, 'latitude', true );
						$long = get_post_meta( $pad, 'longitude', true );

						$options .= "<option value='$pad' data-lat='$lat' data-long='$long' data-title='$title'>$title</option>";
					}
				}

				$input = "<select class='form-select placeholder-toggle inital$class' id='$id' name='$id' $required>$options</select>";
				break;

			default:
				$input = "";
		}
	}

	($required === 'required') ? $label_required = "<span class='required'>*</span>" : $label_required = "";

	$html = "
		<div class='watersharing-row'>
			<label for='$id' class='watersharing-form-label'>$label$label_required</label>
			<div class='watersharing-input-col'>
				$input
			</div>
		</div>
	";

	return $html;
}


// function to build out request form
function buildRequestForm($type = "", $title = "") {
	$html = "";

	// setup the fields for the form
	$well_pad = buildFormField( 'well_pad', 'Well Pads', 'pads', '', 'Create A New Pad' );
	$well_name = buildFormField('well_name', 'Pad Name', 'text', 'required', 'Pad Name');
	$latlong = buildFormField('Coordinates', 'Coordinates', 'latlong', 'required');
	$dates = buildFormField('date_range', 'Date Range', 'date', 'required');
	$rate = buildFormField('rate_bpd', 'Rate (bpd)', 'number', 'required', 'Rate in barrels per day');

	($type === 'share_supply' || $type === 'trade_supply') ? $transport = buildFormField('transport_radius', 'Transport Radius (mi)', 'number', 'required', 'Range in miles') : $transport = "";

	#Trade Specific Fields
	$trade = ($type === 'trade_supply' || $type === 'trade_demand');
	
	
	$trade ? $accept_trucks = buildFormField('toggle', 'Accept Trucks', 'toggle', 'required'): $accept_trucks = "";
	
	// $accept_pipes = 
	 $bid_type = buildFormField('bid_type', 'Bid Type', 'bid_type', 'required');
	$trade ? $bid_amount = buildFormField('bid_amount', 'Bid Amount', 'text', 'required', 'Bid Amount') : $bid_amount = ""; 
	$trade ? $bid_units = buildFormField('bid_units', 'Bid Units', 'bid_units', 'required'): $bid_units = "";

	$trade ? $trucks = "
				<div class='watersharing-row no-margin-bottom trucks' style = 'display:none'>			
					<label class='watersharing-form-label'>Trucks<span class='required'>*</span></label>
					<div class='watersharing-input-col'>
						<div class='watersharing-row'>
								<div class='watersharing-text-col'>
									<input type='text' class='form-control' placeholder='Radius' id='truck_transport_radius' name='truck_transport_radius' required>
								</div>
								<div class='watersharing-text-col'>
									<input type='text' class='form-control' placeholder='Bid' id='truck_transport_bid' name='truck_transport_bid' required>
								</div>
								<div class='watersharing-text-col'>
									<input type='text' class='form-control' placeholder='Capacity' id='truck_transport_capacity' name='truck_transport_capacity' required>
								</div>
							</div>
					</div>
				</div>
			": $trucks = "";

	$trade ? $layflats = buildFormField('layflats', 'Layflats', 'layflats', 'required'): $layflats = "";

	//add an accordion here

	$trade ? $quality_disclosures = "
		<div class='watersharing-row'>			
			<label class='watersharing-form-label'>Quality Disclosures</label>
		</div>
	"
	: $quality_disclosures = "";

	$trade ? $tss = buildFormField('tss', 'TSS', 'quality_disclosure', 'required'): $tss = "";
	$trade ? $tds = buildFormField('tds', 'TDS', 'quality_disclosure', 'required'): $tds = "";
	$trade ? $chloride = buildFormField('chloride', 'Chloride', 'quality_disclosure', 'required'): $chloride = "";
	$trade ? $barium = buildFormField('barium', 'Barium', 'quality_disclosure', 'required'): $barium = "";
	$trade ? $calciumcarbonate = buildFormField('calciumcarbonate', 'Calcium Carbonate', 'quality_disclosure', 'required'): $calciumcarbonate = "";
	$trade ? $iron = buildFormField('iron', 'Iron', 'quality_disclosure', 'required'): $iron = "";
	$trade ? $boron = buildFormField('boron', 'Boron', 'quality_disclosure', 'required'): $boron = "";
	$trade ? $hydrogensulfide = buildFormField('hydrogensulfide', 'Hydrogen Sulfide', 'quality_disclosure', 'required'): $hydrogensulfide = "";
	$trade ? $norm = buildFormField('norm', 'Norm', 'quality_disclosure', 'required'): $norm = "";


	$water_quality = buildFormField('water_quality', 'Water Quality', 'text', '');

	$action = esc_url( admin_url('admin-post.php') );
	$form = "
	<form action='$action' method='POST' id='create-post-form' class='watersharing-form'>
		<input type='hidden' name='action' value='create_water_request'>
		<input type='hidden' name='redirect_success' value='/dashboard'>
		<input type='hidden' name='redirect_failure' value='/404'>
		$well_pad
		$well_name
		$latlong
		$dates
		$rate
		$transport
		$bid_type
		$bid_amount
		$bid_units
		$accept_trucks
		$trucks
		$layflats
		$quality_disclosures
		$tss
		$tds
		$chloride
		$barium
		$calciumcarbonate
		$iron
		$boron
		$hydrogensulfide
		$norm

		<div class='watersharing-section-break'>
			<div class='watersharing-info-text'>Optional fields:</div>
		</div>
		$water_quality
		<input type='hidden' name='post_type' value='$type'>
		<div class='watersharing-row'>
			<label class='watersharing-form-label'></label>
			<div class='watersharing-input-col'>
				<button type='submit' class='watersharing-submit-button'>Submit</button>
			</div>
		</div>
	</form>
	";


	$html = "
		<div class='watersharing-card-wrap'>
			<div class='watersharing-card-inner'>
				<div class='watersharing-card-header'>
					<span class='watersharing-card-title'>$title</span>
				</div>
				<div class='watersharing-card-body'>
				$form
				</div>
			</div>
		</div>
	";


	return $html;
}


// function to lookup matches from the match_request record
function lookupMatches( $post_id = '', $post_type = '' ) {

	if($post_type === 'share_supply'){
		$post_type = 'producer_request';
	}
	elseif($post_type === 'share_demand'){
		$post_type = 'consumption_request';
	}
	elseif($post_type === 'trade_supply'){
		$post_type = 'producer_trade';
	}
	elseif($post_type === 'trade_demand'){
		$post_type = 'consumption_trade';
	}

	// query for the matches
	$query = new WP_Query(
		array(
			'no_found_rows'				=> false,
			'update_post_meta_cache'	=> false,
			'update_post_term_cache'	=> false,
			'post_type' => (strpos($post_type, 'share') !== false) ? 'matched_shares' : 'matched_trades',
			'posts_per_page'			=> -1,
			'fields'					=> 'ids',
			'meta_query'				=> array(
				'relation'		=> 'AND',
				array(
					'key'		=> $post_type,
					'value'		=> $post_id,
					'compare'	=> 'LIKE'
				),
				array(
					'key'		=> 'match_status',
					'value'		=> 'decline',
					'compare'	=> 'NOT IN'
				)
			)
		)
	);

	if ( $query->posts ) {
		return $query->posts;
	}
	wp_reset_postdata();
}


// function to build out a table of requests for a user
function buildRequestTable( $type = '' ) {
	$rows = "";

	// query for the requsts
	$query = new WP_Query(
		array(
			'no_found_rows'				=> false,
			'update_post_meta_cache'	=> false,
			'update_post_term_cache'	=> false,
			'author'					=> get_current_user_id(),
			'post_type'					=> $type,
			'post_status'				=> 'publish',
			'posts_per_page'			=> -1,
			'fields'					=> 'ids',
			'meta_key'          		=> 'status',
			'orderby'           		=> 'meta_value',
			'order'            			=> 'DESC'
		)
	);

	$data = $query->get_posts();

	// iterate through each row
	if( !empty( $data ) ) {
		$number = 1;
		foreach( $data as $post ) {
			( get_post_meta( $post, 'well_name', true ) ) ? $well = get_post_meta( $post, 'well_name', true ) : $well = "";
			( get_post_meta( $post, 'status', true ) ) ? $status = "<span class='status-" . get_post_meta( $post, 'status', true ) . "'>" . get_post_meta( $post, 'status', true ) . "</span>" : $status = "";

			$start = get_post_meta( $post, 'start_date', true );
			( $start ) ? $start = DateTime::createFromFormat('Y-m-d', $start)->format('m/d/Y') : "";
			$end = get_post_meta( $post, 'end_date', true );
			( $end ) ? $end = DateTime::createFromFormat('Y-m-d', $end)->format('m/d/Y') : "";
			$range = "$start - $end";

			( get_post_meta( $post, 'rate_bpd', true ) ) ? $rate = get_post_meta( $post, 'rate_bpd', true ) : $rate = "";

			// check for matches
			$match_rows = "";
			$match_prompt = "<span class='matches no-match'><i class='fa-solid fa-bullseye'></i>Not Found</span>";
			$toggle_disabled = " disabled";

			$lookups = lookupMatches( $post, $type );
			if( $lookups ) {
				$count = 0;

				// build out the match record view
				foreach( $lookups as $lookup ) {
					$count++;

					( $type === 'share_supply' ) ? $user_interaction = 'producer_approval' : $user_interaction = 'consumption_approval';
					$user_action = get_post_meta( $lookup, $user_interaction, true );
					$avoided = get_post_meta( $lookup, 'disposal_avoided', true );
					$fullfilled = get_post_meta( $lookup, 'matched_rate', true );
					$lookup_distance = get_post_meta( $lookup, 'matched_distance', true );
					$lookup_status = get_post_meta( $lookup, 'match_status', true );

					#Share Conditions
					if($type === 'share_supply') { 
						$match_type = 'consumption_request'; 
						$match_post_type = 'share_supply';
					}
					elseif($type === 'share_demand') {
						$match_type = 'producer_request'; 
						$match_post_type = 'share_demand';
					}

					#Trade Conditions
					if($type === 'trade_supply') {
						$match_type = 'consumption_trade';
						$match_post_type = 'trade_demand';
					}
					elseif($type === 'trade_demand') {
						$match_type = 'producer_trade'; 
						$match_post_type = 'trade_demand';
					}

					$match_record = get_post_meta( $lookup, $match_type, true );
					$match_id = $match_record;
					$match_op = get_the_author_meta( $match_record, 'company_name', true );

					$match_start = get_post_meta( $match_record, 'start_date', true );
					( $match_start ) ? $match_start = DateTime::createFromFormat('Y-m-d', $match_start)->format('m/d/Y') : "";
					$match_end = get_post_meta( $match_record, 'end_date', true );
					( $match_end ) ? $match_end = DateTime::createFromFormat('Y-m-d', $match_end)->format('m/d/Y') : "";
					$match_range = "$match_start - $match_end";

					$approve_actions = "
							<a class='watersharing-match-action approval approve-action' onclick='void(0)' data-lookup='$lookup' data-parent='$post' data-match='$match_id' data-match-type='$match_post_type' data-action='approve' data-table='$type-RequestTable'><i class='fa-solid fa-thumbs-up'></i> Approve</a>
							<a class='watersharing-match-action approval decline-action' onclick='void(0)' data-lookup='$lookup' data-parent='$post' data-match='$match_id' data-match-type='$match_post_type' data-action='decline' data-table='$type-RequestTable'><i class='fa-solid fa-thumbs-down'></i> Decline</a>
						";

					if ($user_action) {
						if ($user_action === 'approve') {
							$approve_actions = "
									<a class='watersharing-match-action approval approve-action checked'><i class='fa-solid fa-thumbs-up'></i> Approve</a>
									<a class='watersharing-match-action approval decline-action disabled'><i class='fa-solid fa-thumbs-down'></i> Decline</a>
								";
						}

						if ($user_action === 'decline') {
							$approve_actions = "
									<a class='watersharing-match-action approval approve-action disabled'><i class='fa-solid fa-thumbs-up'></i> Approve</a>
									<a class='watersharing-match-action approval decline-action checked'><i class='fa-solid fa-thumbs-down'></i> Decline</a>
								";
						}
					}

					// check if match is approved
					if ($lookup_status === 'approved') {

						$name = get_userdata( get_post_field( 'post_author', $match_record ) )->first_name . ' ' . get_userdata( get_post_field( 'post_author', $match_record ) )->last_name;
						$phone = get_user_meta( get_post_field( 'post_author', $match_record ), 'phone_number', true );
						$email = get_userdata( get_post_field( 'post_author', $match_record ) )->user_email;

						$contact = "
								<div class='watersharing-col-third watersharing-contact'>
									<span class='heading'>Contact Information</span>
									<span>$name</<span>
									<span><a href='tel:$phone'>$phone</a></span>
									<span><a href='mailto:$email'>$email</a></span>
								</div>
							";
					} else {
						$contact = "
								<div class='watersharing-col-third watersharing-no-contact'>
									<div class='no-contact'>
										<span>Approval Pending</span>
									</div>
								</div>
							";
					}

					//Added logic for trading
					( $type === 'share_demand' || $type === 'trade_demand') ? $avoid_label = "Sourced Water Saved (bbl)" : $avoid_label = "Disposal Avoided (bbl)";

					$match_rows .= "
							<div>
								<div class='watersharing-row watersharing-match-block'>
									<div class='watersharing-match-detail'>
										<div class='watersharing-row'>
											<div class='watersharing-col watersharing-match-col'>
												<div class='watersharing-row'>
													<div class='watersharing-col-half'>
														<strong>Matched Operator:</strong> $match_op
													</div>
													<div class='watersharing-col-half'>
														$approve_actions
													</div>
												</div>
											</div>
											<div class='watersharing-col-half watersharing-match-col'>
												<strong>Dates:</strong> $match_range
											</div>
											<div class='watersharing-col-half watersharing-match-col'>
												<strong>Distance (miles):</strong> $lookup_distance
											</div>
											<div class='watersharing-col-half watersharing-match-col'>
												<strong>Rate (bpd):</strong> $fullfilled
											</div>
											<div class='watersharing-col-half watersharing-match-col'>
												<strong>$avoid_label:</strong> $avoided
											</div>
										</div>
									</div>
									$contact
								</div>
							</div>
						";

					$match_prompt = "<span class='matches matched'><i class='fa-solid fa-bullseye'></i><strong>$count</strong> Matches Found</span>";
					$toggle_disabled = "";
				}
			}

			( isset( get_post_meta( $post, 'status', true )['value'] ) && get_post_meta( $post, 'status', true ) === 'closed' ) ? $row_class = " closed" : $row_class = "";
			$rows .= "
					<tr class='watersharing-request-row$row_class' data-row-number='row-$number'>
						<td class='align-middle hide-on-mobile'><input class='watersharing-input-row' type='checkbox' name='post_ids[]' value='$post' data-watershare-type='$type' /></td>
						<td class='align-middle'><strong class='label show-on-mobile'>Pad Name: </strong>$well</td>
						<td class='align-middle'><strong class='label show-on-mobile'>Date Range: </strong>$range</td>
						<td class='align-middle'><strong class='label show-on-mobile'>Status: </strong>$status</td>
						<td class='align-middle'><strong class='label show-on-mobile'>Rate (bbp): </strong>$rate</td>
						<td class='align-middle'><strong class='label show-on-mobile'>Match Found? </strong>$match_prompt</td>
						<td class='align-middle text-center'>
							<a class='watersharing-match-action toggle-row$toggle_disabled'>
								<i class='fa fa-chevron-right'></i>
							</a>
						</td>
					</tr>
					<tr class='watersharing-request-detail collapse' data-row-number='row-$number'>
						<td class='align-middle d-none'><input class='watersharing-input-row' type='checkbox' name='post_ids[]' value='$post' data-watershare-type='$type' /></td>
						<td class='align-middle d-none'><strong class='label show-on-mobile'>Pad Name: </strong>$well</td>
						<td class='align-middle d-none'><strong class='label show-on-mobile'>Date Range: </strong>$range</td>
						<td class='align-middle d-none'><strong class='label show-on-mobile'>Status: </strong>$status</td>
						<td class='align-middle d-none'><strong class='label show-on-mobile'>Rate (bbp): </strong>$rate</td>
						<td class='align-middle d-none'><strong class='label show-on-mobile'>Match Found? </strong>$match_prompt</td>
						<td class='align-middle d-none'></td>
						<td colspan='7'>
							$match_rows
						</td>
					</tr>
				";

			$number++;
		}
	}

	wp_reset_postdata();

	// build out the table
	$action = admin_url('admin-post.php');

	$table = "
		<form id='$type-status-form' method='post' action='$action?action=change_post_status'>
			<table class='watersharing-table tablesorter' id='$type-RequestTable'>
				<thead>
					<tr>
						<th class='nosort'></th>
						<th>Pad Name</th>
						<th>Date Range</th>
						<th>Status</th>
						<th>Rate (bpd)</th>
						<th>Match Found?</th>
						<th class='nosort' width='50px' data-sort='false'></th>
					</tr>
				</thead>
				<tbody>
					$rows
				</tbody>
			</table>

			<div class='hide-on-mobile'>
				<select name='post_action' id='post_action' class='user-select'>
					<option value='' selected hidden disabled>Manage Selection</option>
					<option value='close'>Close Request(s)</option>
					<option value='delete'>Delete Request(s)</option>
				</select>
				<input id='$type-status-submit' type='submit' name='submit' class='watersharing-submit-button post-status-submit' value='Apply' disabled/>
			</div>
		</form>
	";

	return $table;
}

?>
