<?php

namespace App\Enums;

enum ProductType: string
{
    case PublicRelease = 'public_release';
    case CommunitySystem = 'community_system';
    case OpenSource = 'open_source';
    case CompetitionPrototype = 'competition_prototype';
    case InternalProject = 'internal_project';
}
