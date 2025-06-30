<?php

namespace App\Enum;

enum PageSection: string
{
    case HERO_SECTION = "hero_section";
    case ABOUT_SECTION = "about_section";
    case ABOUT_SECTION_INFO = "about_section_info";
    case OUR_MISSION = "our_mission";
    case OUR_VALUE = "our_value";
    case PLATFORM_OVERVIEW = "platform_overview";
    case PLATFORM_OVERVIEW_SLIDER = "platform_overview_slider";
    case FAQ_SECTION = "faq";
}
