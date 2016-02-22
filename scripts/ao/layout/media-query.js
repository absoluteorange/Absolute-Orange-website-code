define(['jquery','lib/lodash','lib/dom-ready','model/breakpoints',"lib/signals"], 
    function ($,_,domReady,breakpoints,Signals) {

    var media=function(){
        var _currentGroup,
            _isFixedWidthLayout=false,
            _groupChangedSignal= new Signals.Signal(),
            getWidth=function(){
                return  window.innerWidth;
            };

        this.init=function(){
            _currentGroup=this.getGroup();
            $(window).resize(_.debounce(this.checkGroup.bind(this), 500));
        };

        this.checkGroup=function(){
            var group=this.getGroup();
            if(group!=this.getCurrentGroup()){
                _currentGroup=group;
                _groupChangedSignal.dispatch(group);
            }
        };


        this.subscribe=function(event,callback){
            switch(event){
                case 'groupChanged':
                    _groupChangedSignal.add(callback);
                    break;
            }
        };

        this.getCurrentGroup=function(){
            return _currentGroup;
        };

        this.isMobileDevice=function(){
            return isMobileDevice();
        };

        this.isTablet=function(andUp){
            var group=this.getGroup();
            if(group==3 && andUp===false){
                return true;
            }else if((group==3 ||group==4)  && andUp){
                return true;
            }
            return false;
        };

        this.getGroup=function(){
            if(this.isFixedWidthLayout()){
                return 4;
            }
            var width=getWidth();
            for(var i in breakpoints){
                if(width>=breakpoints[i].s && width<=breakpoints[i].f){
                    return Number(i);
                }
            }
        };

        this.isCurrentGroupInPermittedGroupString=function(str){
           var groups;
           if((typeof str =="undefined")|| str===""){
               return true;
           }
            if((typeof str =="number")){
                str=str.toString();
            }
            groups=str.split('_');
            if(typeof groups!='object'){
                groups=[str];
            }
            if((typeof this.getGroup()!="undefined") && (typeof this.getGroup().toString=="function") && groups.indexOf(this.getGroup().toString())!=-1){
                   return true;
            }
            return false;
        };



        this.setFixedWidthLayout=function(state){
            _isFixedWidthLayout=state;
        };

        this.isFixedWidthLayout=function(){
            return _isFixedWidthLayout;
        };

        domReady(this.init.bind(this));
    };
    return new media();

});



