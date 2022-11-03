<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\MigrateModel;

class ImportHSNSAC extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:hsnsac';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import hsn sac code';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $model = new MigrateModel();
        $data = array();
        $links[] = 'https://cleartax.in/s/chapter-1-live-animal-products-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-2-meat-edible-meat-offal-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-3-fish-crustaceans-molluscs-aquatic-invertebrates-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-4-dairy-eggs-natural-honey-edible-animal-product-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-5-animal-origin-products-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-6-vegetable-products-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-7-edible-vegetable-roots-tuber-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-8-edible-fruit-nut-citrus-fruit-peel-melons-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-9-coffee-tea-mate-spices-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-10-cereals-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-11-milling-products-malt-starches-inulin-wheat-gluten-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-12-oil-seeds-oleaginous-fruits-grainsseeds-fruit-industrial-medical-plants-straw-fodder-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-13-lac-gums-resins-vegetable-saps-extracts-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-14-vegetable-plaiting-product-materials-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-15-edible-animal-vegetable-fats-oils-cleavage-products-prepared-waxes-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-16-fish-meat-crustaceans-molluscs-aquatic-invertebrates-preparations-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-17-sugar-confectionery-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-18-cocoa-preparations-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-19-cereals-flour-starch-milk-pastrycooks-products-preparations-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-20-vegetables-fruit-nuts-preparations-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-21-miscellaneous-edible-preparations-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-22-beverages-spirits-vinegar-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-23-residues-waste-food-industries-prepared-animal-fodder-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-24-tobacco-manufactured-substitutes-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-25-mineral-products-salt-sulphur-earths-stone-plastering-materials-lime-cement-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-26-ores-slag-ash-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-27-mineral-fuels-oils-distillation-bituminous-substances-mineral-waxes-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-28-organic-inorganic-chemicals-compounds-precious-metals-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-29-elements-isotopes-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-30-pharmaceutical-products-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-31-fertilizers-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-32-tanning-dyeing-extracts-dyes-pigments-paints-varnishes-putty-mastics-inks-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-33-essential-oils-resinoids-perfumery-cosmetic-toilet-preparations-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-34-soap-washing-lubricating-artificial-waxes-polishing-scouring-candles-modelling-pastes-dental-waxes-preparations-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-35-albuminoidal-substances-starches-glues-enzymes-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-36-explosives-pyrotechnic-matches-pyrophoric-alloys-combustible-preparations-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-37-photographic-cinematographic-goods-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-38-chemical-products-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-39-plastics-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-40-rubber-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-41-raw-hides-skins-furskins-leather-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-42-leather-travel-goods-handbags-containers-animal-silkworm-gut-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-43-furskins-artificial-fur-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-44-wood-charcoal-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-45-cork-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-46-straw-esparto-basketware-wickerwork-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-47-pulp-wood-fibrous-cellulosic-material-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-48-waste-scrap-paper-paperboard-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-49-printed-books-newspapers-pictures-manuscripts-typescripts-plans-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-50-textiles-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-51-wool-animal-hair-horsehair-yarn-woven-fabric-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-52-cotton-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-53-vegetable-textile-fibres-paper-yarn-woven-fabrics-paper-yarn-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-54-man-made-filaments-strip-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-55-man-made-staple-fibres-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-56-wadding-felt-nonwovens-yarns-twine-cordage-ropes-cables-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-57-carpets-floor-coverings-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-58-woven-fabrics-tufted-textile-lace-tapestries-trimmings-embroidery-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-59-impregnated-coated-covered-laminated-textile-fabrics-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-60-knitted-crocheted-fabrics-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-61-apparel-clothing-accessories-knitted-crocheted-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-62-apparel-clothing-accessories-not-knitted-crocheted-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-63-sets-worn-clothing-textile-rags-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-64-footwear-gaiters-parts-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-65-headgear-parts-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-66-sun-umbrellas-walking-sticks-seat-whips-riding-crops-parts-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-67-prepared-feathers-artificial-flowers-human-hair-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-68-stone-plaster-cement-asbestos-mica-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-69-ceramic-products-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-70-glass-glassware-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-71-natural-cultured-pearls-precious-semi-precious-stones-metals-clad-imitation-jewelry-coin-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-72-iron-steel-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-73-iron-steel-articles-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-74-copper-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-75-nickel-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-76-aluminium-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-77-reserved-for-possible-future-use-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-78-lead-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-79-zinc-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-80-tin-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-81-cermets-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-82-tools-implements-cutlery-spoons-forks-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-83-base-metal-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-84-nuclear-reactors-boilers-machinery-mechanical-appliances-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-85-electrical-machinery-equipment-sound-recorders-reproducers-television-image-parts-accessories-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-86-railway-tramway-locomotives-rolling-stock-track-fixtures-fittings-electro-mechanical-traffic-signalling-equipment-parts-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-87-vehicles-railway-tramway-rolling-stock-parts-accessories-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-88-aircraft-spacecraft-parts-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-89-ships-boats-floating-structures-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-90-optical-photographic-cinematographic-measuring-checking-precision-medical-surgical-instruments-apparatus-parts-accessories-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-91-clocks-watches-parts-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-92-musical-instruments-parts-accessories-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-93-arms-ammunitions-parts-accessories-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-94-furniture-mattresses-cushions-lamps-lightings-illuminated-signs-name-plates-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-95-toys-games-sports-parts-accessories-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-96-miscellaneous-manufactured-articles-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-97-art-collectors-pieces-antiques-works-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-98-laboratory-chemicals-baggage-imports-gst-rate-hsn-code';
        $links[] = 'https://cleartax.in/s/chapter-99-services-sac-code-gst-rate';
        foreach ($links as $key => $link) {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $link,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_POSTFIELDS => "",
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            $response = mb_convert_encoding($response, 'utf-8');

            $type = ($link == 'https://cleartax.in/s/chapter-99-services-sac-code-gst-rate') ? 'Service' : 'Goods';
            $chapter = $key + 1;
            $starttable = strpos($response, '<table');
            $endtable = strpos($response, '</table>');
            $stringtr = substr($response, $starttable, $endtable - $starttable);
            $tablerows = explode('<tr>', $stringtr);
            foreach ($tablerows as $row) {
                $stringtd = str_replace('</tr>', '', $row);
                $rowcolumn = explode('<td>', $stringtd);
                if (count($rowcolumn) > 3) {

                    $code = $this->getString($rowcolumn[1]);
                    $des = $this->getString($rowcolumn[2]);
                    $tax = $this->getString($rowcolumn[3]);
                    $tax = (is_numeric($tax)) ? $tax : 0;
                    if ($chapter < 10 && strlen($code) < 8) {
                        $code = '0' . $code;
                    }
                    $data[] = array('type' => $type, 'code' => $code, 'des' => $des, 'tax' => $tax, 'chapter' => $chapter, 'link' => $link);
                }
            }
        }

        foreach ($data as $r) {
            $model->saveHSNSAC($r['type'], $r['code'], $r['des'], $r['tax'], $r['chapter'], $r['link']);
        }
    }

    function getString($str)
    {
        $str = str_replace('</td>', '', $str);
        return trim($str);
    }
}
