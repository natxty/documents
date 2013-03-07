<templates:siteheader 
    title="LSA, LIFECODES LSA-MIC, Recombinant MICA Single Antigen Beads, Luminex xMAP Technology"
    pagetitle="LSA MIC" 
    bodyclass="lsaMic" 
    heroimage="../im/products/hero_products_LSA.jpg"
    section="products" 
    subsection="lifecodes-antibody"
    subsectionselected = "mic"
    topnavselected="products" 
    leftnavselected="tranplant" 
    breadcrumbs="<li><a href='./'>Products & Services</a></li><li>&rsaquo;</li><li class='last act'><a href=''>LSA MIC</a></li>" 
    javascript="<script type='text/javascript'>$(document).ready(function(){$('#sectnav li').hide();$('#tabs').tabs();});</script>" 
    runat="server" />

<script src="/js/filetree/jqueryFileTree.js"></script>
<style>
	@import url('/js/filetree/jqueryFileTree.css');
	
	#filebrowser {
		width: 500px;
		height: 300px;
		margin-left: 30px;
		overflow: scroll;
	}
</style>
<script>
$(document).ready(function() {
	$('#filebrowser').fileTree({ 
		root: '../uploads/File/product/', 
		script: 'jqueryFileTree.php' }, 
		
		function(file) { 
			window.open(file,'_blank'); 
		});
});
</script>    
				<div id="tabs">
                    <ul>
                        <li><a href="#about">About</a></li>
                        <li><a href="#products">Products</a></li>
                        <li><a href="#packageInsert">Package Insert</a></li>
                        <li><a href="#msds">MSDS</a></li>
                        <li><a href="#regulatory">Regulatory</a></li>
                        <li><a href="#lotspecific">Lot Specific</a></li>
                        <li><a href="#softwaredownloads">Downloads</a></li>
                    </ul>
					<div class="tab" id="about">
                    <h4>LIFECODES LSA&#153; MIC</h4><br/>
						<p>
						LIFECODES LSA- MIC provides recombinant MICA single antigen beads for the detection of MICA antibodies in serum.</p>
						
                        <p>LIFECODES LSA-MIC facilitates identification of immunogenic epitopes in sera sensitized to MICA. The redundancy of epitopes helps ensure the correct identification of anti-MICA reactivity. Luminex xMAP technology provides flow cytometric sensitivity to identify low titer antibodies in subject sera.</p>
                        <p>When you need to know more than just HLA.</p>
                        <p>For Research Use only. </p>
				    </div>
					<div class="tab" id="products">
						<img src="../im/products/transplant-diagnostics/lifecodes-anitbody-detction-products/lsa-mic/transdi-adp-lsamic-products.jpg" width="560" height="80" />
					</div>
					<div class="tab" id="packageInsert">
						<p>
							<a class="pdf" href="/pdfs/pi/LC1017RUO.4 - LIFECODES LSA MIC Product Insert_RUO.pdf">Click to Download</a>
						</p>
					</div>
					<div class="tab" id="msds">
						<a class="pdf" href="/pdfs/MSDS/LC983E.4 - MSDS LSA Kits - English.pdf">Click to Download</a>
					</div>
					<div class="tab" id="regulatory">LSA-MIC kit is available for Research Use Only.</div>
					<div class="tab" id="lotspecific">
                    
                    <table width="600" border="0" cellspacing="0" cellpadding="0" class="gridtable">
                    <tr class="mgmtcreds small">
    <td width="50" align="left" valign="top" style="width:50px;"><p><b>Cat. #</b></p></td>
    <td width="90" align="left" valign="top" style="width:90px;"><p><b>LIFECODES Product</b></p></td>
    <td width="60" align="left" valign="top" style="width:40px;"><p><b>Lot #</b></p></td>
    <td width="30" align="left" valign="top" style="width:30px;"><b>Cert.</b></td>
    <td width="40" align="left" valign="top" style="width:40px;"><b>TT/RS</b></td>
    <td width="50" align="left" valign="top" style="width:50px;"><b>Panel</b></td>
    <td width="60" align="left" valign="top" style="width:60px;"><p><b>Probe Hit Charts</b></p></td>
    <td width="50" align="left" valign="top" style="width:50px;"><b>Core Seq.</b></td>
    <td width="50" align="left" valign="top" style="width:50px;"><b>Exp.</b></td>
  </tr>
    <tr  class="mgmtcreds small">
      <td width="50" align="left" valign="top" style="width:50px;">265300</td>
      <td width="90" align="left" valign="top" style="width:90px;">LSA-MIC Kit</td>
      <td width="60" align="left" valign="top" style="width:40px;">12090A</td>
      <td width="30" align="left" valign="top" style="width:30px;"><a href="/pdfs/documents/LOT SPECIFIC/LSAproducts-lotSpecific/265300-QC1481RUO.6-LIFECODES-LSA-MIC-Lot-12090A-2011-12-31.pdf">RUO</a></td>
      <td width="40" align="left" valign="top" style="width:40px;"><a href="/pdfs/documents/LOT SPECIFIC/LSAproducts-lotSpecific/LC1018.1-LSA-MIC-worksheet-Lot-12090A-2011-12-rev-1.pdf">RS</a><br />
  <a href="/pdfs/documents/LOT SPECIFIC/LSAproducts-lotSpecific/LC1018.1-LSA-MIC-worksheetLot12090A-2011-12-NEW.pdf">RS</a></td>
      <td width="50" align="left" valign="top" style="width:50px;">&nbsp;</td>
      <td width="60" align="left" valign="top" style="width:60px;">&nbsp;</td>
      <td width="50" align="left" valign="top" style="width:50px;">&nbsp;</td>
      <td width="50" align="left" valign="top" style="width:50px;">2011-12-31</td>
    </tr>
  </table>
                    
                  </div>
					<div class="tab" id="softwaredownloads">
						
						<div id="filebrowser">
						
						</div>
					</div>
					
				</div>

<templates:sitefooter runat="server" />