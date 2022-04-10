(function() {
    'use strict';

    angular.module('mcms.frontEnd.ssg')
        .controller('SsgHomeController',Controller);

    Controller.$inject = ['Init', 'SsgService', '$rootScope', '$scope'];

    function Controller(InitialLoad, service, $rootScope, $scope) {
        this.building = false;
        this.buildComplete = false;
        this.buildFailed = false;
        this.buildProgress = false;
        this.currentBuild;
        this.config = InitialLoad.config;
        this.items = InitialLoad.items;
        this.progress = '';
        this.progressOutput = '';
        this.buildIterations = 0;
        this.buildStages = [
            {
                "name": "queued",
                "started_on": "2022-04-10T11:51:38.194997Z",
                "ended_on": "2022-04-10T11:51:38.167473Z",
                "status": "success"
            },
            {
                "name": "initialize",
                "started_on": "2022-04-10T11:51:38.167473Z",
                "ended_on": "2022-04-10T11:51:40.344092Z",
                "status": "success"
            },
            {
                "name": "clone_repo",
                "started_on": "2022-04-10T11:51:40.344092Z",
                "ended_on": "2022-04-10T11:51:42.041839Z",
                "status": "success"
            },
            {
                "name": "build",
                "started_on": "2022-04-10T11:51:42.041839Z",
                "ended_on": null,
                "status": "active"
            },
            {
                "name": "deploy",
                "started_on": null,
                "ended_on": null,
                "status": "idle"
            }
        ]

        this.formatBuildProgress = (stages) => {
            return stages
                .filter(stage => stage.status !== 'idle')
                .map(stage => {
                    let buildingDots = '';
                    if (stage.name === 'build' && stage.status === 'active') {
                        this.buildIterations++;
                        for (let i =0; this.buildIterations > i; i++) {
                            buildingDots += '.';
                        }
                    }
                    else if (stage.name === 'build' && stage.status === 'success') {
                        this.buildIterations = 0;
                    }

                    let ret = '';
                    const statusStr = (stage.status !== 'idle') ? ` [${stage.status}]` : '';


                    switch (stage.name) {
                        case 'queued': ret =  `Queued ${statusStr}`;
                        break;
                        case 'initialize': ret =  `Initializing ${statusStr}`;
                        break;
                        case 'clone_repo': ret =  `Cloning Repo ${statusStr}`;
                        break;
                        case 'build': ret =  `Building ${buildingDots} ${statusStr}`;
                        break;
                        case 'deploy': ret =  `Uploading to server ${statusStr}`;
                        break;
                    }

                    return ret;
                }).join('\n');
        }

        // Once the build is complete, refresh the data
        $rootScope.$on('buildCompleted', ($event, data) => {
            service.all()
                .then(items => {
                    this.items = items;
                    this.building = false;
                    this.buildComplete = true;
                    this.getItems();
                    $scope.$apply();
                    setTimeout(() => {
                        this.buildComplete = false;

                    }, 5000);
                });
        });

        $rootScope.$on('buildFailed', ($event, data) => {
            this.buildFailed = true;
            this.building = false;
            $scope.$apply();
            this.getItems();

            setTimeout(() => {
                this.buildFailed = false;
                $scope.$apply();
            }, 5000);
        });

        $rootScope.$on('buildProgress', ($event, data) => {
            this.progressOutput = this.formatBuildProgress(data.stages);
            $scope.$apply();
        });

        $rootScope.$on('buildStarted', ($event, data) => {
            this.getItems()
                .then(items => {
                    $scope.$apply();
                });
        });

        this.getItems = () => {
            return service.all()
                .then(items => {
                    this.items = items;
                });
        }

        this.viewItem = (id) => {
            const item = service.deployment(id);
            console.log(item);
        }

        this.build = () => {
            this.building = true;

            service.startBuild()
                .then(res => {
                    this.currentBuild = res;
                    return res;
                })
                .then(() => service.all())
                .then(items => this.items = items)
                .catch(e => console.log(e));
        }
    }

})();
