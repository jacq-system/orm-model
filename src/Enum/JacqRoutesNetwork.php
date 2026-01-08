<?php declare(strict_types=1);

namespace JACQ\Enum;

enum JacqRoutesNetwork: string
{
    case output_image_endpoint = JacqAppNetwork::Output->value . '/image';
    case output_specimenDetail = JacqAppNetwork::Output->value . '/detail';
    case services_rest_iiif_manifest = JacqAppNetwork::Services->value . '/iiif/manifest';
    case services_rest_images_europeana = JacqAppNetwork::Services->value . '/images/europeana/';
    case services_rest_sid_multi = JacqAppNetwork::Services->value . '/stableIdentifier/multi';
    case services_rest_images_show = JacqAppNetwork::Services->value . '/images/show';
    case services_rest_images_download = JacqAppNetwork::Services->value . '/images/download';
    case services_rest_iiif_createManifest = JacqAppNetwork::Services->value . '/iiif/createManifest';

}
