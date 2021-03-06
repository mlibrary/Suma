'use strict';

angular.module('sumaAnalysis')
  .factory('actsLocs', function () {
    function calculateDepthAndTooltip (item, list, root, depth) {
      var parent;

      depth = depth || {depth: 0, tooltipTitle: item.title, ancestors: []};
      if (parseInt(item.parent, 10) === parseInt(root, 10)) {
        return depth;
      }

      parent = _.find(list, {'id': item.parent});

      depth.depth += 1;
      depth.tooltipTitle = parent.title + ': ' + depth.tooltipTitle;
      depth.ancestors.push(parent.id);

      return calculateDepthAndTooltip(parent, list, root, depth);
    }

    function processActivities (activities, activityGroups) {
      var activityList = [],
          activityGroupsHash;

      // Sort activities and activity groups
      activities = _.sortBy(activities, 'rank');
      activityGroups = _.sortBy(activityGroups, 'rank');

      activityGroupsHash = _.object(_.map(activityGroups, function (aGrp) {
        return [aGrp.id, aGrp.title];
      }));

      // For each activity group, build a list of activities
      _.each(activityGroups, function (activityGroup) {
          // Add activity group metadata to activityGroupList array
          activityList.push({
            'id'     : activityGroup.id,
            'rank'   : activityGroup.rank,
            'title'  : activityGroup.title,
            'type'   : 'activityGroup',
            'depth'  : 0,
            'filter' : 'allow',
            'enabled': true
          });

          // Loop over activities and add the ones belonging to the current activityGroup
          _.each(activities, function (activity) {
            if (activity.activityGroup === activityGroup.id) {
              // Add activities to activityList array behind proper activityGroup
              activityList.push({
                'id'                : activity.id,
                'rank'              : activity.rank,
                'title'             : activity.title,
                'type'              : 'activity',
                'depth'             : 1,
                'activityGroup'     : activityGroup.id,
                'activityGroupTitle': activityGroupsHash[activityGroup.id],
                'tooltipTitle'      : activityGroupsHash[activityGroup.id] + ': ' + activity.title,
                'altName'           : activityGroupsHash[activityGroup.id] + ': ' + activity.title,
                'filter'            : 'allow',
                'enabled'           : true
              });
            }
          });
        });

      return activityList;
    }

    function processLocations (locations, root) {
      return _.map(locations, function (loc, index, list) {
        var depth = calculateDepthAndTooltip(loc, list, root);

        loc.depth        = depth.depth;
        loc.tooltipTitle = depth.tooltipTitle;
        loc.ancestors    = depth.ancestors;
        loc.filter       = true;
        loc.enabled      = true;

        return loc;
      });
    }

    return {
      get: function (init) {
        return {
          activities: processActivities(init.dictionary.activities, init.dictionary.activityGroups),
          locations: processLocations(init.dictionary.locations, init.rootLocation)
        };
      }
    };
  });
