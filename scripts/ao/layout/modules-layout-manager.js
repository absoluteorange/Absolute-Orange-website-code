define([
	'jquery',
	'layout/media-query'

	], function ($,MediaQuery) {	 	

 	var ModuleLayoutManager= function() {

		this.currentLayout=-1;
		this.modules=[];
		this.layoutIDS=['#additional-modules-group_1_2','#additional-modules-group_3']
		this.layoutGroupsA=[1,2];
		this.layoutGroupsB=[3];

		this.init=function(){
			
			this.checkLayout();
			MediaQuery.subscribe('groupChanged',this.checkLayout.bind(this));
		}

		this.addModule=function(module){
			this.modules.push(module);
		};

		this.checkLayout=function(){
			
			if(this.currentLayout!==this.getLayout()){
				this.updateLayout();
			}
		};

		this.getLayout=function(){
			var layout=0;
			if(_.includes(this.layoutGroupsB,MediaQuery.getGroup())){
				layout=1
			}
			return layout;		
		};

		this.updateLayout=function(){

			var layout=this.getLayout(),
				source,destination;

			if(layout===1){
				source=$(this.layoutIDS[0]);
				destination=$(this.layoutIDS[1]);
			}else{
				source=$(this.layoutIDS[1]);
				destination=$(this.layoutIDS[0]);
			}
			source.empty();

			_.each(this.modules,function(module){
					destination.append(module);
			});		
		
			this.currentLayout=layout;		
		};	

	};

	return  new ModuleLayoutManager();; 	

 });


