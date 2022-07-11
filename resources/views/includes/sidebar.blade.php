@php
       $routeArray = app('request')->route()->getAction();
        $controllerAction = class_basename($routeArray['controller']);
        list($controller, $action) = explode('@', $controllerAction);
        $Action = str_replace('Controller','',$controller.'.'.request()->route()->getActionMethod());
@endphp


  <div class="az-sidebar">
    <div class="az-sidebar-header">
      <a href="{{url('')}}" class="az-logo" style="
      text-transform: uppercase;color: #1d263d;"><img class="wd-45 ht-40 mg-l-10 bd bd-gray-500 rounded-10"
        src="<?=url('');?>/img/alder_logo.png" >&nbsp;ADLER</a>
    </div><!-- az-sidebar-header -->
    
    <div class="az-sidebar-loggedin">
      <div class="az-img-user online"><img src="<?=url('');?>/img/profile.png" alt=""></div>
      <div class="media-body">
        <h6>Aziana Pechon</h6>
        <span>Premium Member</span>
      </div><!-- media-body -->
    </div><!-- az-sidebar-loggedin -->
    <div class="az-sidebar-body">
      <ul class="nav">
        <li class="nav-label">Main Menu</li>
        <li class="nav-item @if (in_array($Action,['Inventory.get_purchase_reqisition','Inventory.add_purchase_reqisition','Inventory.edit_purchase_reqisition',
        'Inventory.get_purchase_reqisition_item','Inventory.edit_purchase_reqisition_item',
        'Inventory.add_purchase_reqisition_item'])) {{'active show'}} @endif ">
          <a href="#" class="nav-link with-sub"><i class="fas fa-shopping-cart" style="font-size: 17px;"></i> Purchase details</a>
          <ul class="nav-sub">
            <li class="nav-sub-item"><a href="#" class="nav-sub-link">Purchase Order</a></li>
            <li class="nav-sub-item @if(in_array($Action,['Inventory.get_purchase_reqisition','Inventory.add_purchase_reqisition','Inventory.edit_purchase_reqisition',
            'Inventory.get_purchase_reqisition_item','Inventory.edit_purchase_reqisition_item',
            'Inventory.add_purchase_reqisition_item'])){{'active'}} @endif"><a href="{{url('inventory/get-purchase-reqisition')}}"  class="nav-sub-link">Purchase Reqisition</a></li>
            <li class="nav-sub-item @if(in_array($Action,['Inventory.get_purchase_reqisition','Inventory.add_purchase_reqisition','Inventory.edit_purchase_reqisition',
            'Inventory.get_purchase_reqisition_item','Inventory.edit_purchase_reqisition_item',
            'Inventory.add_purchase_reqisition_item'])){{'active'}} @endif"><a href="{{url('inventory/purchase-reqisition/approval')}}"  class="nav-sub-link">Reqisition Approval</a></li>
            <li class="nav-sub-item @if(in_array($Action,['Inventory.get_purchase_reqisition','Inventory.add_purchase_reqisition','Inventory.edit_purchase_reqisition',
            'Inventory.get_purchase_reqisition_item','Inventory.edit_purchase_reqisition_item',
            'Inventory.add_purchase_reqisition_item'])){{'active'}} @endif"><a href="{{url('inventory/purchase-reqisition/approval')}}"  class="nav-sub-link">Rejected list</a></li>
          </ul>
        </li><!-- nav-item -->

        

        <li class="nav-item">
          <a href="#" class="nav-link with-sub"><i class="typcn typcn-tabs-outline"></i></i>
           Quotation
          </a>
          <ul class="nav-sub">
            <li class="nav-sub-item"><a href="{{url('inventory/quotation')}}" class="nav-sub-link">Request for Quotation</a></li>
            <li class="nav-sub-item"><a href="{{url('inventory/quotation')}}"  class="nav-sub-link">Purchase Reqisition</a></li>
          </ul>
        </li>


        <li class="nav-item">
          <a href="#" class="nav-link with-sub"><i class="typcn typcn-document"></i>Apps &amp; Pages</a>
          <ul class="nav-sub">
            <li class="nav-sub-item">
              <a href="app-mail.html" class="nav-sub-link">Mailbox</a>
            </li>
            <li class="nav-sub-item">
              <a href="app-chat.html" class="nav-sub-link">Chat</a>
            </li>
            <li class="nav-sub-item">
              <a href="app-calendar.html" class="nav-sub-link">Calendar</a>
            </li>
            <li class="nav-sub-item">
              <a href="app-contacts.html" class="nav-sub-link">Contacts</a>
            </li>
            <li class="nav-sub-item"><a href="app-kanban.html" class="nav-sub-link">Kanban</a></li>
            <li class="nav-sub-item"><a href="app-tickets.html" class="nav-sub-link">Tickets</a></li>
            <li class="nav-sub-item"><a href="page-profile.html" class="nav-sub-link">Profile</a></li>
            <li class="nav-sub-item"><a href="page-invoice.html" class="nav-sub-link">Invoice</a></li>
            <li class="nav-sub-item"><a href="page-signin.html" class="nav-sub-link">Sign In</a></li>
            <li class="nav-sub-item"><a href="page-signup.html" class="nav-sub-link">Sign Up</a></li>
            <li class="nav-sub-item"><a href="page-404.html" class="nav-sub-link">Page 404</a></li>
            <li class="nav-sub-item"><a href="page-faq.html" class="nav-sub-link">Faq</a></li>
            <li class="nav-sub-item"><a href="page-news-grid.html" class="nav-sub-link">News Grid</a></li>
            <li class="nav-sub-item"><a href="page-product-catalogue.html" class="nav-sub-link">Product Catalogue</a></li>
            <li class="nav-sub-item"><a href="page-project-list.html" class="nav-sub-link">Project List</a></li>
            <li class="nav-sub-item"><a href="page-order.html" class="nav-sub-link">Orders</a></li>
            <li class="nav-sub-item"><a href="page-pricing.html" class="nav-sub-link">Pricing</a></li>
            <li class="nav-sub-item"><a href="landing-sass.html" class="nav-sub-link">Landing Page</a></li>
            
          </ul>
        </li><!-- nav-item -->
        <li class="nav-item">
          <a href="#" class="nav-link with-sub"><i class="typcn typcn-book"></i>UI Elements</a>
          <ul class="nav-sub">
            <li class="nav-sub-item"><a href="elem-accordion.html" class="nav-sub-link">Accordion</a></li>
            <li class="nav-sub-item"><a href="elem-alerts.html" class="nav-sub-link">Alerts</a></li>
            <li class="nav-sub-item"><a href="elem-avatar.html" class="nav-sub-link">Avatar</a></li>
            <li class="nav-sub-item"><a href="elem-badge.html" class="nav-sub-link">Badge</a></li>
            <li class="nav-sub-item"><a href="elem-breadcrumbs.html" class="nav-sub-link">Breadcrumbs</a></li>
            <li class="nav-sub-item"><a href="elem-buttons.html" class="nav-sub-link">Buttons</a></li>
            <li class="nav-sub-item"><a href="elem-cards.html" class="nav-sub-link">Cards</a></li>
            <li class="nav-sub-item"><a href="elem-carousel.html" class="nav-sub-link">Carousel</a></li>
            <li class="nav-sub-item"><a href="elem-collapse.html" class="nav-sub-link">Collapse</a></li>
            <li class="nav-sub-item"><a href="elem-dropdown.html" class="nav-sub-link">Dropdown</a></li>
            <li class="nav-sub-item"><a href="elem-icons.html" class="nav-sub-link">Icons</a></li>
            <li class="nav-sub-item"><a href="elem-images.html" class="nav-sub-link">Images</a></li>
            <li class="nav-sub-item"><a href="elem-list-group.html" class="nav-sub-link">List Group</a></li>
            <li class="nav-sub-item"><a href="elem-media-object.html" class="nav-sub-link">Media Object</a></li>
            <li class="nav-sub-item"><a href="elem-modals.html" class="nav-sub-link">Modals</a></li>
            <li class="nav-sub-item"><a href="elem-navigation.html" class="nav-sub-link">Navigation</a></li>
            <li class="nav-sub-item"><a href="elem-pagination.html" class="nav-sub-link">Pagination</a></li>
            <li class="nav-sub-item"><a href="elem-popover.html" class="nav-sub-link">Popover</a></li>
            <li class="nav-sub-item"><a href="elem-progress.html" class="nav-sub-link">Progress</a></li>
            <li class="nav-sub-item"><a href="elem-spinners.html" class="nav-sub-link">Spinners</a></li>
            <li class="nav-sub-item"><a href="elem-toast.html" class="nav-sub-link">Toast</a></li>
            <li class="nav-sub-item"><a href="elem-tooltip.html" class="nav-sub-link">Tooltip</a></li>
          </ul>
        </li><!-- nav-item -->
        <li class="nav-item">
          <a href="#" class="nav-link with-sub"><i class="typcn typcn-edit"></i>Forms</a>
          <ul class="nav-sub">
            <li class="nav-sub-item"><a href="form-elements.html" class="nav-sub-link">Form Elements</a></li>
            <li class="nav-sub-item"><a href="form-layouts.html" class="nav-sub-link">Form Layouts</a></li>
            <li class="nav-sub-item"><a href="form-validation.html" class="nav-sub-link">Form Validation</a></li>
            <li class="nav-sub-item"><a href="form-wizards.html" class="nav-sub-link">Form Wizards</a></li>
            <li class="nav-sub-item"><a href="form-editor.html" class="nav-sub-link">WYSIWYG Editor</a></li>
          </ul>
        </li><!-- nav-item -->
        <li class="nav-item">
          <a href="#" class="nav-link with-sub"><i class="typcn typcn-chart-bar-outline"></i>Charts</a>
          <ul class="nav-sub">
            <li class="nav-sub-item"><a href="chart-morris.html" class="nav-sub-link">Morris Charts</a></li>
            <li class="nav-sub-item"><a href="chart-flot.html" class="nav-sub-link">Flot Charts</a></li>
            <li class="nav-sub-item"><a href="chart-chartjs.html" class="nav-sub-link">ChartJS</a></li>
            <li class="nav-sub-item"><a href="chart-sparkline.html" class="nav-sub-link">Sparkline</a></li>
            <li class="nav-sub-item"><a href="chart-peity.html" class="nav-sub-link">Peity</a></li>
          </ul>
        </li><!-- nav-item -->
        <li class="nav-item">
          <a href="#" class="nav-link with-sub"><i class="typcn typcn-map"></i>Maps</a>
          <ul class="nav-sub">
            <li class="nav-sub-item"><a href="map-google.html" class="nav-sub-link">Google Maps</a></li>
            <li class="nav-sub-item"><a href="map-leaflet.html" class="nav-sub-link">Leaflet</a></li>
            <li class="nav-sub-item"><a href="map-vector.html" class="nav-sub-link">Vector Maps</a></li>
          </ul>
        </li><!-- nav-item -->
        <li class="nav-item">
          <a href="#" class="nav-link with-sub"><i class="typcn typcn-tabs-outline"></i>Tables</a>
          <ul class="nav-sub">
            <li class="nav-sub-item"><a href="table-basic.html" class="nav-sub-link">Basic Tables</a></li>
            <li class="nav-sub-item"><a href="table-data.html" class="nav-sub-link">Data Tables</a></li>
          </ul>
        </li><!-- nav-item -->
        <li class="nav-item">
          <a href="#" class="nav-link with-sub"><i class="typcn typcn-archive"></i>Utilities</a>
          <ul class="nav-sub">
            <li class="nav-sub-item"><a href="util-background.html" class="nav-sub-link">Background</a></li>
            <li class="nav-sub-item"><a href="util-border.html" class="nav-sub-link">Border</a></li>
            <li class="nav-sub-item"><a href="util-display.html" class="nav-sub-link">Display</a></li>
            <li class="nav-sub-item"><a href="util-flex.html" class="nav-sub-link">Flex</a></li>
            <li class="nav-sub-item"><a href="util-height.html" class="nav-sub-link">Height</a></li>
            <li class="nav-sub-item"><a href="util-margin.html" class="nav-sub-link">Margin</a></li>
            <li class="nav-sub-item"><a href="util-padding.html" class="nav-sub-link">Padding</a></li>
            <li class="nav-sub-item"><a href="util-position.html" class="nav-sub-link">Position</a></li>
            <li class="nav-sub-item"><a href="util-typography.html" class="nav-sub-link">Typography</a></li>
            <li class="nav-sub-item"><a href="util-width.html" class="nav-sub-link">Width</a></li>
            <li class="nav-sub-item"><a href="util-extras.html" class="nav-sub-link">Extras</a></li>
          </ul>
        </li><!-- nav-item -->
      </ul><!-- nav -->
    </div><!-- az-sidebar-body -->
  </div><!-- az-sidebar -->
